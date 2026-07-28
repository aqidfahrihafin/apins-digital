<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'phone',
        'is_active',
        'email_verified_at',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Avatar URL accessor
     */
    public function getAvatarUrlAttribute(): string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : 'https://ui-avatars.com/api/?background=006b58&color=fff&name=' . urlencode($this->name);
    }

    /**
     * Custom Role List (safe, no conflict)
     */
    public function getRoleNamesListAttribute(): string
    {
        return $this->roles->pluck('name')->implode(', ');
    }

    /**
     * Relationships
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPosts::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comments::class);
    }

    public function teamMember()
    {
        return $this->hasOne(TeamMembers::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLogs::class);
    }
}
