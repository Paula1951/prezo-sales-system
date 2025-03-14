<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $fillable = [
        'date_sale',
        'product_id',
        'quantity_sold',
        'sale_price',
        'profit_margin',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
