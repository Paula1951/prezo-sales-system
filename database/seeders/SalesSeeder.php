<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;      
use App\Models\Product;    
use App\Models\SaleDetail; 

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sales')->insert([
            ['sale_id' => 1, 'product_id' => 1, 'quantity' => 10, 'price' => 10.00],
            ['sale_id' => 1, 'product_id' => 2, 'quantity' => 5, 'price' => 10.00],
            ['sale_id' => 2, 'product_id' => 1, 'quantity' => 8, 'price' => 10.00],
            ['sale_id' => 2, 'product_id' => 2, 'quantity' => 2, 'price' => 8.00],
        ]);
    }
}
