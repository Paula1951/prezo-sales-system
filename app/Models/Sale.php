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
        'total_price',
    ];

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
