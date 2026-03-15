<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purcheseproduct extends Model
{
    use HasFactory;

    public function purchases()
    {
        return $this->belongsTo(Purchase::class, 'purchese_id');
    }
}
