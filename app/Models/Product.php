<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'food_cost',
    ];    

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
