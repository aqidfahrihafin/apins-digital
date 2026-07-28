<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portofolios extends Model
{
    protected $table = 'portofolios';

    protected $fillable = [
        'category_id', 'client_name', 'client_logo', 'title', 'slug',
        'description', 'challenge', 'solution', 'result', 'technologies',
        'live_url', 'github_url', 'start_date', 'end_date',
        'is_featured', 'is_active', 'view_count',
    ];

    protected $casts = [
        'technologies' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // PASTIKAN ini menggunakan PortofoliosCategory (bukan PortfoliosCategory)
    public function category(): BelongsTo
    {
        return $this->belongsTo(PortofoliosCategory::class, 'category_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PortofoliosImages::class, 'portofolios_id')->orderBy('order_index');
    }
}
