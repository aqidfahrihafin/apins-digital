<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogTags extends Model
{
    protected $table = 'blog_tags';

    protected $fillable = ['name', 'slug'];

    public function posts()
    {
        return $this->belongsToMany(BlogPosts::class, 'blog_post_tags', 'tag_id', 'post_id');
    }
}
