<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasFactory;

class Sale extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'sale_date',
        'product_id',
        'quantity',
        'sale_price',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
