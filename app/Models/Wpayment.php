<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wpayment extends Model
{
    use HasFactory;
    public function wsale()
    {
        return $this->belongsTo(Wsale::class);
    }
}
