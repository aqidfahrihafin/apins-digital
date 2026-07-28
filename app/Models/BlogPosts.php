<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPosts extends Model
{
    protected $table = 'blog_posts';

    protected $fillable = [
        'category_id', 'author_id', 'title', 'slug', 'excerpt',
        'content', 'featured_image', 'is_published', 'published_at', 'view_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true)->where('published_at', '<=', now());
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTags::class, 'blog_post_tags', 'post_id', 'tag_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class, 'post_id')
            ->whereNull('parent_id')
            ->where('is_approved', true);
    }

    public function allComments()
    {
        return $this->hasMany(Comments::class, 'post_id');
    }
}
