<?php

namespace Tests\Feature;

use App\Models\BlogPosts;
use App\Models\BlogCategory;
use App\Models\BlogTags;
use App\Models\Comments;
use App\Models\Portofolios;
use App\Models\PortofoliosCategory;
use App\Models\PortofoliosImages;
use App\Models\PageView;
use App\Models\Service;
use App\Models\ServiceDetails;
use App\Models\Settings;
use App\Models\SiteConfigs;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_a_contact_message_can_be_submitted(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '08123456789',
            'service' => 'Web Development',
            'message' => 'Saya ingin membuat website.',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('contacts', [
            'email' => 'test@example.com',
            'message' => 'Saya ingin membuat website.',
        ]);
    }

    public function test_an_email_can_subscribe_to_the_newsletter(): void
    {
        $response = $this->post('/newsletter', [
            'email' => 'subscriber@example.com',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('newsletters', [
            'email' => 'subscriber@example.com',
            'is_active' => true,
        ]);
    }

    public function test_active_admin_content_is_rendered_on_the_homepage(): void
    {
        Settings::create([
            'headline' => 'Headline dari Admin',
            'hero_title' => 'Hero Admin',
            'is_active' => true,
        ]);
        SiteConfigs::set('about_title', 'Tentang dari Admin', 'about');

        $service = Service::create([
            'name' => 'Cloud Integration',
            'slug' => 'cloud-integration',
            'short_desc' => 'Integrasi sistem cloud.',
            'long_desc' => 'Detail layanan dari panel admin.',
            'is_active' => true,
        ]);
        ServiceDetails::create([
            'service_id' => $service->id,
            'feature' => 'Monitoring real-time',
            'order_index' => 1,
        ]);

        $category = PortofoliosCategory::create([
            'name' => 'Web App',
            'slug' => 'web-app',
        ]);
        $portfolio = Portofolios::create([
            'category_id' => $category->id,
            'title' => 'Portal Pelanggan',
            'slug' => 'portal-pelanggan',
            'description' => 'Portofolio dari admin.',
            'technologies' => ['Laravel', 'Tailwind'],
            'is_active' => true,
        ]);
        PortofoliosImages::create([
            'portofolios_id' => $portfolio->id,
            'image_url' => 'portofolios/portal.jpg',
            'is_primary' => true,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSeeText('Hero Admin')
            ->assertSeeText('Cloud Integration')
            ->assertSeeText('Monitoring real-time')
            ->assertSeeText('Portal Pelanggan')
            ->assertSeeText('Tentang dari Admin');
    }

    public function test_blog_tags_comments_and_publication_status_are_synchronized(): void
    {
        $author = User::factory()->create();
        $post = BlogPosts::create([
            'author_id' => $author->id,
            'title' => 'Artikel Terpublikasi',
            'slug' => 'artikel-terpublikasi',
            'content' => '<p>Konten artikel.</p>',
            'is_published' => true,
            'published_at' => now()->subMinute(),
        ]);
        $tag = BlogTags::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $post->tags()->attach($tag);
        Comments::create([
            'post_id' => $post->id,
            'name' => 'Pembaca',
            'email' => 'reader@example.com',
            'content' => 'Komentar disetujui.',
            'is_approved' => true,
        ]);

        $this->get(route('blog.index', ['tag' => 'Laravel']))
            ->assertOk()
            ->assertSeeText('Artikel Terpublikasi');
        $this->get(route('blog.show', $post->slug))
            ->assertOk()
            ->assertSeeText('Laravel')
            ->assertSeeText('Komentar disetujui.');

        $this->post(route('blog.comments.store', $post->slug), [
            'name' => 'Komentator Baru',
            'email' => 'new@example.com',
            'content' => 'Menunggu moderasi.',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'email' => 'new@example.com',
            'is_approved' => true,
        ]);

        $this->post(route('blog.comments.store', $post->slug), [
            'name' => 'Komentator Spam',
            'email' => 'spam@example.com',
            'content' => 'Free money https://spam.example dan https://promo.example',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('comments', [
            'email' => 'spam@example.com',
            'is_approved' => false,
        ]);
    }

    public function test_super_admin_can_open_synchronized_admin_resources(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $role = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin->assignRole($role);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('admin-native.css');
        $this->actingAs($admin)
            ->get('/admin/services')
            ->assertOk();
        $this->actingAs($admin)
            ->get('/admin/service-details')
            ->assertRedirect('/admin/services');
        $this->actingAs($admin)
            ->get('/admin/comments')
            ->assertOk();
        $this->actingAs($admin)
            ->get('/admin/site-configs')
            ->assertOk();
    }

    public function test_super_admin_can_manage_native_roles_and_permissions(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $superAdmin = Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
        $admin->assignRole($superAdmin);

        $this->actingAs($admin)->get('/admin/permissions/create')
            ->assertOk()
            ->assertSee('Name');

        $this->actingAs($admin)->post('/admin/permissions', [
            'name' => 'publish special reports',
        ])->assertRedirect('/admin/permissions');

        $permission = Permission::where('name', 'publish_special_reports')->firstOrFail();

        $this->actingAs($admin)->get('/admin/roles/create')
            ->assertOk()
            ->assertSee('Permission');

        $this->actingAs($admin)->post('/admin/roles', [
            'name' => 'report_manager',
            'permissions' => [$permission->name],
        ])->assertRedirect('/admin/roles');

        $this->assertTrue(Role::findByName('report_manager')->hasPermissionTo($permission));

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Admin Konten',
            'email' => 'content-admin@example.com',
            'password' => 'password-aman',
            'is_active' => '1',
            'roles' => ['report_manager'],
        ])->assertRedirect('/admin/users');

        $createdUser = User::where('email', 'content-admin@example.com')->firstOrFail();
        $this->assertTrue($createdUser->is_active);
        $this->assertTrue($createdUser->hasRole('report_manager'));
    }

    public function test_admin_can_manage_combined_service_details_and_article_tags(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole(Role::create(['name' => 'super_admin', 'guard_name' => 'web']));

        $this->actingAs($admin)->post('/admin/services', [
            'name' => 'Product Engineering',
            'short_desc' => 'Membangun produk digital.',
            'is_active' => '1',
            'details' => [
                1785242850819 => ['feature' => 'Discovery kebutuhan', 'icon' => 'search'],
                1785242850820 => ['feature' => 'Pengembangan iteratif', 'icon' => 'check_circle'],
            ],
        ])->assertRedirect('/admin/services');

        $service = Service::where('slug', 'product-engineering')->firstOrFail();
        $this->assertSame(2, $service->details()->count());

        $category = BlogCategory::create(['name' => 'Teknologi', 'slug' => 'teknologi']);
        $tag = BlogTags::create(['name' => 'Laravel', 'slug' => 'laravel']);
        $otherUser = User::factory()->create();

        $this->actingAs($admin)->get('/admin/blog-posts/create')
            ->assertOk()
            ->assertSee('data-editor', false)
            ->assertDontSee('name="author_id"', false);

        $this->actingAs($admin)->post('/admin/blog-posts', [
            'category_id' => $category->id,
            'author_id' => $otherUser->id,
            'title' => 'Artikel Terintegrasi',
            'excerpt' => 'Ringkasan artikel.',
            'content' => 'Isi artikel.',
            'is_published' => '1',
            'published_at' => now()->format('Y-m-d'),
            'tags' => [$tag->id],
        ])->assertRedirect('/admin/blog-posts');

        $post = BlogPosts::where('slug', 'artikel-terintegrasi')->firstOrFail();
        $this->assertSame($category->id, $post->category_id);
        $this->assertSame($admin->id, $post->author_id);
        $this->assertTrue($post->tags->contains($tag));
    }

    public function test_admin_tables_render_nested_settings_and_can_quick_toggle_status(): void
    {
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole(Role::create(['name' => 'super_admin', 'guard_name' => 'web']));
        Settings::create([
            'headline' => 'Solusi Digital',
            'services' => [
                ['name' => 'Website', 'description' => 'Aplikasi web'],
                ['name' => 'IoT', 'description' => 'Perangkat terhubung'],
            ],
            'is_active' => true,
        ]);
        $service = Service::create([
            'name' => 'Layanan Toggle',
            'slug' => 'layanan-toggle',
            'short_desc' => 'Pengujian status.',
            'is_active' => true,
        ]);

        $this->actingAs($admin)->get('/admin/settings')
            ->assertOk()
            ->assertSee('Website')
            ->assertSee('IoT');
        $setting = Settings::firstOrFail();
        $this->actingAs($admin)->get("/admin/settings/{$setting->id}/edit")
            ->assertOk()
            ->assertDontSee('name="services"', false);

        $this->actingAs($admin)
            ->patch("/admin/services/{$service->id}/toggle/is_active")
            ->assertRedirect();

        $this->assertFalse($service->fresh()->is_active);

        $category = PortofoliosCategory::create([
            'name' => 'Website',
            'slug' => 'website',
        ]);
        $this->actingAs($admin)
            ->patch("/admin/portfolio-categories/{$category->id}/toggle/is_active")
            ->assertStatus(422);
    }

    public function test_portfolio_and_gallery_are_managed_in_one_form(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_active' => true]);
        $admin->assignRole(Role::create(['name' => 'super_admin', 'guard_name' => 'web']));
        $category = PortofoliosCategory::create(['name' => 'Aplikasi', 'slug' => 'aplikasi']);

        $this->actingAs($admin)->get('/admin/portofolios/create')
            ->assertOk()
            ->assertSee('Galeri karya')
            ->assertSee('Tambah gambar');

        $this->actingAs($admin)->post('/admin/portofolios', [
            'category_id' => $category->id,
            'title' => 'Dashboard Terpadu',
            'description' => 'Dashboard operasional perusahaan.',
            'is_active' => '1',
            'gallery_present' => '1',
            'primary_image' => 'new_1',
            'gallery' => [
                'new_1' => ['image' => UploadedFile::fake()->image('utama.jpg'), 'caption' => 'Halaman utama'],
                'new_2' => ['image' => UploadedFile::fake()->image('laporan.jpg'), 'caption' => 'Halaman laporan'],
            ],
        ])->assertRedirect('/admin/portofolios');

        $portfolio = Portofolios::where('title', 'Dashboard Terpadu')->firstOrFail();
        $this->assertSame(2, $portfolio->images()->count());
        $this->assertSame('Halaman utama', $portfolio->images()->where('is_primary', true)->firstOrFail()->caption);
        $this->actingAs($admin)->get('/admin/portfolio-images')->assertRedirect('/admin/portofolios');
    }

    public function test_public_page_views_are_tracked_anonymously(): void
    {
        $this->withHeader('User-Agent', 'Mozilla/5.0 (iPhone; Mobile)')
            ->withHeader('Referer', 'https://google.com/search')
            ->get('/')
            ->assertOk();

        $view = PageView::firstOrFail();
        $this->assertSame('/', $view->path);
        $this->assertSame('home', $view->route_name);
        $this->assertSame('mobile', $view->device);
        $this->assertSame('google.com', $view->referrer_host);
        $this->assertSame(64, strlen($view->visitor_hash));
        $this->assertGreaterThan(0, $view->response_ms);
    }
}
