<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name', 'slug', 'short_desc', 'long_desc', 'icon', 'image', 'is_active', 'order_index',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function details()
    {
        return $this->hasMany(ServiceDetails::class, 'service_id')->orderBy('order_index');
    }
}
