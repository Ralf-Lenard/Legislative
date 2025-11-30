<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResolutionsSeeder extends Seeder
{
    public function run(): void
    {
        // Example: create 10 resolutions
        for ($i = 1; $i <= 10; $i++) {
            DB::table('resolutions')->insert([
                'resolutions_number' => '2025-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'title_resolutions' => 'Resolution Title ' . $i,
                'description_resolutions' => 'This is a sample description for resolution ' . $i,
                'date_approved_resolutions' => now()->subDays(rand(0, 365)),
                'file_path_resolutions' => null,
                'image_resolutions' => null,
                'author_resolutions' => 'SB Member ' . chr(64 + $i), // e.g., A, B, C
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
