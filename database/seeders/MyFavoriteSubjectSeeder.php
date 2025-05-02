<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyFavoriteSubject;

class MyFavoriteSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // ... existing code ...
        $subjects = [
            [
                'title' => 'God of War',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1tmu.jpg',
                'description' => 'God of War (2018) - An epic action game about a father and son journey through Norse mythology.',
                'category' => 'Action Adventure',
                'rating' => 5
            ],
            [
                'title' => 'Hollow Knight',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1rgi.jpg',
                'description' => 'Hollow Knight - A beautiful, haunting metroidvania through a vast ruined kingdom of insects.',
                'category' => 'Metroidvania',
                'rating' => 4
            ],
            [
                'title' => 'Elden Ring',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co4jni.jpg',
                'description' => 'Elden Ring - An epic action RPG set in a vast open world created by FromSoftware and George R.R. Martin.',
                'category' => 'Action RPG',
                'rating' => 5
            ],
            [
                'title' => 'Dark Souls III',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co2p3j.jpg',
                'description' => 'Dark Souls III - The final chapter in the Dark Souls trilogy, featuring challenging combat and deep lore.',
                'category' => 'Action RPG',
                'rating' => 4
            ],
            [
                'title' => 'The Last of Us',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co1r7h.jpg',
                'description' => 'The Last of Us - A post-apocalyptic story of survival and the bond between a smuggler and a young girl.',
                'category' => 'Action Adventure',
                'rating' => 5
            ],
            [
                'title' => 'Grand Theft Auto V',
                'image' => 'https://images.igdb.com/igdb/image/upload/t_cover_big/co2lbd.jpg',
                'description' => 'Grand Theft Auto V - An open-world action game set in the fictional city of Los Santos.',
                'category' => 'Action Adventure',
                'rating' => 4
            ],
        ];

        // Clear existing subjects first
        MyFavoriteSubject::truncate();

        // Create new subjects
        foreach ($subjects as $subject) {
            MyFavoriteSubject::create($subject);
        }
    }
} 