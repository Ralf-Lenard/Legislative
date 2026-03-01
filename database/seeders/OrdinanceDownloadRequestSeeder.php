<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Ordinance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrdinanceDownloadRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::pluck('id')->toArray(); // get all user IDs
        $ordinances = Ordinance::pluck('id')->toArray(); // get all ordinance IDs

        $statuses = ['pending', 'approved', 'rejected'];
        $validIdTypes = ['Passport', 'Driver’s License', 'PRC ID', 'SSS ID', 'PhilHealth ID'];

        for ($i = 0; $i < 50; $i++) {
            DB::table('ordinance_download_requests')->insert([
                'user_id' => $users[array_rand($users)],
                'ordinance_id' => $ordinances[array_rand($ordinances)],
                'purpose' => fake()->sentence(6),
                'rejection_reason' => fake()->boolean(20) ? fake()->sentence(5) : null, // 20% chance
                'status' => $statuses[array_rand($statuses)],
                'is_downloaded' => fake()->boolean(50),
                'valid_id_type' => $validIdTypes[array_rand($validIdTypes)],
                'valid_id_path' => 'valid_ids/' . Str::random(10) . '.jpg', // fake file path
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}