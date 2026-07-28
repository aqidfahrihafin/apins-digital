<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogPostsTags extends Model
{
    protected $table = 'blog_post_tags'; // nama tabel pivot

    protected $fillable = [
        'blog_post_id',
        'tag_id',
    ];

    // atau pakai $guarded seperti punya Anda
    // protected $guarded = [];

    public $timestamps = true; // jika pivot punya created_at/updated_at

    // Relasi ke BlogPost
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPosts::class, 'blog_post_id');
    }

    // Relasi ke Tag
    public function tag(): BelongsTo
    {
        return $this->belongsTo(BlogTags::class, 'tag_id');
    }
}
