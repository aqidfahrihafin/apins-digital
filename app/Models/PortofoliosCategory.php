<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortofoliosCategory extends Model
{
    protected $table = 'portofolios_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function portofolios(): HasMany
    {
        return $this->hasMany(Portofolios::class, 'category_id');
    }
}
