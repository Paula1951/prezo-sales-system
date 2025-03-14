<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Nachos con guacamole', 
                'food_cost' => 5.51
            ],
            [
                'name' => 'Ron Cola',
                'food_cost' => 2.25
            ],
        ]);
    }
}
