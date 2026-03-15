<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wsale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoiceID',
        'date',
        'wcustomer_id',
        'totalAmount',
        'status',
    ];


    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function wsaleproducts()
    {
        return $this->hasMany(Wsaleproduct::class, 'wsale_id');
    }

    public function wcustomers()
    {
        return $this->belongsTo(Wcustomer::class, 'wcustomer_id');
    }
}
