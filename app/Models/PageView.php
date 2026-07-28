<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $fillable = [
        'path', 'route_name', 'page_title', 'visitor_hash', 'session_hash',
        'device', 'referrer_host', 'response_ms', 'viewed_at',
    ];

    protected $casts = ['viewed_at' => 'datetime'];
}
