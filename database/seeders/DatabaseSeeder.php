<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\MyFavoriteSubjectSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ProductSeeder::class,
            MyFavoriteSubjectSeeder::class,
        ]);
    }
}
