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
            [
                'sale_date' => '2024-07-02',
                'total_price' => null,
            ],
            [
                'sale_date' => '2024-07-03',
                'total_price' => null,
            ],
        ]);
    }
}
