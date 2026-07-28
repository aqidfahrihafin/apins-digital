<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contacts extends Model
{
    protected $table = 'contacts';

    protected $fillable = ['name', 'email', 'phone', 'service', 'message', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }
}
