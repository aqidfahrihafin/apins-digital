<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceDetails extends Model
{
    protected $table = 'service_details';

    protected $fillable = ['service_id', 'feature', 'icon', 'order_index'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
