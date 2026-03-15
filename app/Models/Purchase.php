<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoiceID',
        'date',
        'supplier_id',
        'totalAmount',
        'status',
    ];


    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function purcheseproducts()
    {
        return $this->hasMany(Purcheseproduct::class, 'purchese_id');
    }

    public function suppliers()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
