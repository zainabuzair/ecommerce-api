<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
{
    \App\Models\Product::create([
        'name' => 'Gaming Laptop',
        'description' => 'High performance laptop',
        'price' => 1200.00,
        'stock' => 10
    ]);
}
}
