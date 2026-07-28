<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $fillable = [
        'headline',
        'subheadline',
        'description',
        'hero_title',
        'services',
        'portfolio_button_text',
        'portfolio_url',
        'consultation_button_text',
        'consultation_url',
        'image', // tambahkan ini
        'is_active',
    ];

    protected $casts = [
        'services' => 'array',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (Settings $settings): void {
            if ($settings->is_active) {
                self::whereKeyNot($settings->getKey())->update(['is_active' => false]);
            }
        });
    }

    // Accessor untuk mendapatkan URL lengkap gambar
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/'.$this->image);
        }

        return null;
    }
}
