<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    public function couriers()
    {
        return $this->belongsTo(Courier::class, 'courier_id');
    }

    public function zones()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'area_id');
    }

}
