<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PortofoliosImages extends Model
{
    use HasFactory;

    protected $fillable = [
        'portofolios_id',
        'image_url',
        'caption',
        'is_primary',
        'order_index',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'order_index' => 'integer',
    ];

    // 🔹 RELASI KE PORTOFOLIOS
    public function portofolio()
    {
        return $this->belongsTo(Portofolios::class, 'portofolios_id');
    }
}
