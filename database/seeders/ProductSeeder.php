<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 2000; $i++) {
            DB::table('products')->insert([
                'name' => 'Product ' . $i,
                'description' => 'Deskripsi produk ke-' . $i,
                'price' => rand(1000, 100000) / 100,
                'stock' => rand(0, 100),
                'category_id' => rand(1, 5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
