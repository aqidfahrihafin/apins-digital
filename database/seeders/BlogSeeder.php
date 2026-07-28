<?php

namespace Database\Seeders;

use App\Models\BlogPosts;
use App\Models\BlogTags;
use App\Models\Comments;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $author = User::where('email', 'superadmin@apinsdigital.com')->firstOrFail();
        $tags = collect([
            'laravel' => 'Laravel',
            'transformasi-digital' => 'Transformasi Digital',
            'keamanan' => 'Keamanan',
            'iot' => 'IoT',
        ])->mapWithKeys(function ($name, $slug) {
            $tag = BlogTags::updateOrCreate(['slug' => $slug], ['name' => $name]);

            return [$slug => $tag];
        });

        $posts = [
            ['memilih-teknologi-untuk-bisnis', 'Cara Memilih Teknologi yang Tepat untuk Bisnis', 'Panduan praktis sebelum memulai proyek digital.', ['transformasi-digital', 'keamanan']],
            ['laravel-untuk-aplikasi-modern', 'Mengapa Laravel Cocok untuk Aplikasi Modern', 'Mengenal keunggulan Laravel untuk aplikasi bisnis.', ['laravel', 'keamanan']],
            ['iot-untuk-efisiensi-operasional', 'IoT untuk Efisiensi Operasional', 'Bagaimana data perangkat membantu pengambilan keputusan.', ['iot', 'transformasi-digital']],
        ];

        foreach ($posts as $index => [$slug, $title, $excerpt, $postTags]) {
            $post = BlogPosts::updateOrCreate(
                ['slug' => $slug],
                [
                    'author_id' => $author->id,
                    'title' => $title,
                    'excerpt' => $excerpt,
                    'content' => "<h2>{$title}</h2><p>{$excerpt}</p><p>Setiap bisnis memiliki kebutuhan berbeda. Mulailah dari masalah yang ingin diselesaikan, ukur dampaknya, lalu pilih teknologi yang mudah dirawat dan dikembangkan.</p>",
                    'is_published' => true,
                    'published_at' => now()->subDays($index + 1),
                ],
            );
            $post->tags()->sync(collect($postTags)->map(fn ($tag) => $tags[$tag]->id));

            Comments::updateOrCreate(
                ['post_id' => $post->id, 'email' => "reader{$index}@example.com"],
                [
                    'name' => 'Pembaca Apins',
                    'content' => 'Artikel yang informatif dan mudah dipahami.',
                    'is_approved' => true,
                ],
            );
        }
    }
}
