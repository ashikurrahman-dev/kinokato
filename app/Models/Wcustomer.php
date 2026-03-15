<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wcustomer extends Model
{
    use HasFactory;
    protected $fillable = [
        'wcustomerName',
        'wcustomerPhone',
        'wcustomerEmail',
        'wcustomerAddress',
        'wcustomerProfile',
        'wcustomerCompanyName',
        'wcustomerTotalAmount',
        'wcustomerPaidAmount',
        'wcustomerDueAmount',
        'wcustomerPartialAmount',
        'status',
    ];

    public function wsales()
    {
        return $this->hasMany(Wsale::class, 'wcustomer_id');
    }
}
