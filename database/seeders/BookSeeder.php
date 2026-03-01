<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use Faker\Factory as Faker;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Optional: clear table first
        // Book::truncate();

        for ($i = 1; $i <= 100; $i++) {
            Book::create([
                'title' => $faker->sentence(3), // 3-word title
                'author' => $faker->name,
                'category' => $faker->randomElement(['Fiction', 'Non-Fiction', 'Science', 'History', 'Biography', 'Fantasy', 'Mystery']),
                'published_year' => $faker->year(),
                'description' => $faker->paragraph(2),
                'image' => 'books/sample' . rand(1, 5) . '.jpg', // dummy image path
            ]);
        }
    }
}