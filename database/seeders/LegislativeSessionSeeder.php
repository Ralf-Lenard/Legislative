<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LegislativeSession;
use Carbon\Carbon;

class LegislativeSessionSeeder extends Seeder
{
    public function run(): void
    {
        // Only values allowed by your enum
        $sessionTypes = ['Regular', 'Special'];

        // Optional: clear table first
        // LegislativeSession::truncate();

        for ($i = 1; $i <= 100; $i++) {
            LegislativeSession::create([
                'session_number' => $i,
                'session_title' => 'Legislative Session #' . $i,
                'date_of_session' => Carbon::now()->subDays(rand(0, 365)),
                'session_type' => $sessionTypes[array_rand($sessionTypes)],
                'summary' => 'This session discussed various local ordinances, community programs, budget allocations, and public concerns relevant to the municipality.',
                'images' => [
                    'sessions/sample1.jpg',
                    'sessions/sample2.jpg',
                    'sessions/sample3.jpg'
                ],
            ]);
        }
    }
}