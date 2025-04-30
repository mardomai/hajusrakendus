<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Product;

class ClearProductsSeeder extends Seeder
{
    public function run(): void
    {
        Product::truncate();
    }
} 