<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'God of War',
                'description' => 'Playstation 5 game',
                'price' => 70,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1tmu.jpg'
            ],
            [
                'name' => 'Hollow Knight',
                'description' => 'Metrovania game',
                'price' => 15,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1rgi.jpg'
            ],
            [
                'name' => 'Elden Ring',
                'description' => 'Open World game',
                'price' => 70,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co4jni.jpg'
            ],
            [
                'name' => 'Dark Souls 3',
                'description' => 'Dark Souls game',
                'price' => 30,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co2p3j.jpg'
            ],
            [
                'name' => 'The Last of Us',
                'description' => 'The Last of Us game',
                'price' => 40,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1r7h.jpg'
            ],
            [
                'name' => 'GTA V',
                'description' => 'GTA game',
                'price' => 10,
                'stock' => 100,
                'image_path' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co2lbd.jpg'
            ]
        ];

        Product::truncate();

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 