<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\ProductSeeder;
use Database\Seeders\MyFavoriteSubjectSeeder;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Run the ProductSeeder and MyFavoriteSubjectSeeder
        $this->call([
            ProductSeeder::class,
            MyFavoriteSubjectSeeder::class,
        ]);
    }
}
