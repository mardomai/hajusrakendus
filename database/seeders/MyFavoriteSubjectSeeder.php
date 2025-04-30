<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MyFavoriteSubject;

class MyFavoriteSubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            [
                'title' => 'Maths',
                'image' => 'https://i.imgur.com/7tPNxF4.jpeg',
                'description' => 'Learning about numbers, shapes, and patterns.',
                'category' => 'Mathematics',
                'rating' => 5
            ],
            [
                'title' => 'English',
                'image' => 'https://i.imgur.com/jkgo70t.png',
                'description' => 'Learning about grammar, vocabulary, and reading.',
                'category' => 'English',
                'rating' => 4
            ],
            [
                'title' => 'Estonian',
                'image' => 'https://i.imgur.com/mJCnDGe.png',
                'description' => 'Learning about Estonian grammar, vocabulary, and reading.',
                'category' => 'Estonian',
                'rating' => 5
            ],
            [
                'title' => 'History',
                'image' => 'https://i.imgur.com/jHcyqdD.jpeg',
                'description' => 'Learning about history, events, and people.',
                'category' => 'History',
                'rating' => 4
            ],
            [
                'title' => 'Geography',
                'image' => 'https://i.imgur.com/F03Qlb9.png',
                'description' => 'Learning about geography, maps, and locations.',
                'category' => 'Geography',
                'rating' => 5
            ],
            [
                'title' => 'Biology',
                'image' => 'https://i.imgur.com/OyMrPJf.jpeg',
                'description' => 'Learning about biology, plants, and animals.',
                'category' => 'Biology',
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