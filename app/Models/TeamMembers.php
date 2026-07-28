<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamMembers extends Model
{
    protected $table = 'team_members';

    protected $fillable = ['user_id', 'position', 'order_index', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
