<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wsaleproduct extends Model
{
    use HasFactory;
    public function wsales()
    {
        return $this->belongsTo(Wsale::class, 'wsale_id');
    }
}
