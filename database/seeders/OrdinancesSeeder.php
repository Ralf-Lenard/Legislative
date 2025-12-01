<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdinancesSeeder extends Seeder
{
    public function run(): void
    {
        // Example: create 10 ordinances
        for ($i = 1; $i <= 25; $i++) {
            DB::table('ordinances')->insert([
                'ordinance_number' => '2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'title_ordinances' => 'Ordinance Title ' . $i,
                'description_ordinances' => 'This is a sample description for ordinance ' . $i,
                'date_approved_ordinances' => now()->subDays(rand(0, 365)),
                'file_path_ordinances' => null,
                'image_ordinances' => null,
                'author_ordinances' => 'SB Member ' . chr(64 + $i), // e.g., A, B, C
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
