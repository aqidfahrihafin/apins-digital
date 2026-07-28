<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('blog_posts_tags') && ! Schema::hasTable('blog_post_tags')) {
            Schema::rename('blog_posts_tags', 'blog_post_tags');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('blog_post_tags') && ! Schema::hasTable('blog_posts_tags')) {
            Schema::rename('blog_post_tags', 'blog_posts_tags');
        }
    }
};
