<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles';

    protected $fillable = ['user_id', 'bio', 'position', 'social_links', 'expertise', 'joined_at'];

    protected $casts = [
        'social_links' => 'array',
        'expertise' => 'array',
        'joined_at' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
