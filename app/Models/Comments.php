<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'comments';

    protected $fillable = ['post_id', 'parent_id', 'user_id', 'name', 'email', 'content', 'is_approved'];

    protected $casts = ['is_approved' => 'boolean'];

    public function post()
    {
        return $this->belongsTo(BlogPosts::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(self::class, 'parent_id')
            ->where('is_approved', true)
            ->oldest();
    }
}
