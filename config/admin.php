<?php

use App\Models\ActivityLogs;
use App\Models\BlogPosts;
use App\Models\BlogCategory;
use App\Models\BlogPostsTags;
use App\Models\BlogTags;
use App\Models\Comments;
use App\Models\Contacts;
use App\Models\Faqs;
use App\Models\Newsletters;
use App\Models\Portofolios;
use App\Models\PortofoliosCategory;
use App\Models\PortofoliosImages;
use App\Models\Service;
use App\Models\ServiceDetails;
use App\Models\Settings;
use App\Models\SiteConfigs;
use App\Models\TeamMembers;
use App\Models\Testimonials;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return [
    'groups' => [
        'Website' => [
            'settings' => ['model' => Settings::class, 'label' => 'Hero & identitas', 'icon' => 'web'],
            'site-configs' => ['model' => SiteConfigs::class, 'label' => 'Konfigurasi situs', 'icon' => 'tune'],
            'services' => ['model' => Service::class, 'label' => 'Layanan', 'icon' => 'deployed_code'],
        ],
        'Konten pendukung' => [
            'faqs' => ['model' => Faqs::class, 'label' => 'FAQ', 'icon' => 'quiz'],
            'testimonials' => ['model' => Testimonials::class, 'label' => 'Testimoni', 'icon' => 'reviews'],
            'team-members' => ['model' => TeamMembers::class, 'label' => 'Tim', 'icon' => 'groups'],
        ],
        'Karya' => [
            'portofolios' => ['model' => Portofolios::class, 'label' => 'Daftar karya', 'icon' => 'work'],
            'portfolio-categories' => ['model' => PortofoliosCategory::class, 'label' => 'Kategori karya', 'icon' => 'category'],
        ],
        'Publikasi' => [
            'blog-posts' => ['model' => BlogPosts::class, 'label' => 'Artikel', 'icon' => 'article'],
            'blog-categories' => ['model' => BlogCategory::class, 'label' => 'Kategori artikel', 'icon' => 'category'],
            'blog-tags' => ['model' => BlogTags::class, 'label' => 'Tag artikel', 'icon' => 'sell'],
        ],
        'Interaksi' => [
            'comments' => ['model' => Comments::class, 'label' => 'Komentar', 'icon' => 'forum'],
            'contacts' => ['model' => Contacts::class, 'label' => 'Pesan masuk', 'icon' => 'inbox'],
            'newsletters' => ['model' => Newsletters::class, 'label' => 'Newsletter', 'icon' => 'mark_email_read'],
        ],
        'Akses & sistem' => [
            'users' => ['model' => User::class, 'label' => 'Pengguna', 'icon' => 'manage_accounts'],
            'roles' => ['model' => Role::class, 'label' => 'Role akses', 'icon' => 'admin_panel_settings'],
            'permissions' => ['model' => Permission::class, 'label' => 'Permission', 'icon' => 'key'],
            'activity-logs' => ['model' => ActivityLogs::class, 'label' => 'Log aktivitas', 'icon' => 'history', 'readonly' => true],
        ],
    ],
];
