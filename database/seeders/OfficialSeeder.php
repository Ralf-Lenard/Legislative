<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialSeeder extends Seeder
{
    public function run(): void
    {
        $officials = [
            // 🔵 ELECTED OFFICIALS
            [
                'name' => 'Juan Dela Cruz',
                'position' => 'Vice Mayor / Presiding Officer',
                'main_committee' => null,
                'division' => null,
                'type' => 'official',
                'bio' => 'Serving as Vice Mayor and Presiding Officer of the Sangguniang Bayan.',
            ],
            [
                'name' => 'Maria Santos',
                'position' => 'Councilor',
                'main_committee' => 'Committee on Finance',
                'division' => null,
                'type' => 'official',
                'bio' => 'Chairperson of the Committee on Finance.',
            ],
            [
                'name' => 'Pedro Reyes',
                'position' => 'Councilor',
                'main_committee' => 'Committee on Education',
                'division' => null,
                'type' => 'official',
                'bio' => 'Advocate for youth and education development.',
            ],

            // 🟢 ORGANIZATIONAL CHART
            [
                'name' => 'Ana Lopez',
                'position' => 'Secretary to the Sanggunian',
                'main_committee' => null,
                'division' => 'Administrative Division',
                'type' => 'staff',
                'bio' => 'Handles legislative documentation and records.',
            ],
            [
                'name' => 'Mark Bautista',
                'position' => 'Administrative Officer',
                'main_committee' => null,
                'division' => 'Administrative Division',
                'type' => 'staff',
                'bio' => 'Oversees daily administrative operations.',
            ],
            [
                'name' => 'Liza Mendoza',
                'position' => 'Budget Officer',
                'main_committee' => null,
                'division' => 'Finance Division',
                'type' => 'staff',
                'bio' => 'Responsible for budget preparation and monitoring.',
            ],
        ];

        // 🔁 Add 14 more random staff to make 20 total
        for ($i = 1; $i <= 14; $i++) {
            $officials[] = [
                'name' => fake()->name(),
                'position' => fake()->randomElement([
                    'Legislative Staff Officer',
                    'Clerk',
                    'Records Officer',
                    'Administrative Aide',
                    'Technical Assistant'
                ]),
                'main_committee' => null,
                'division' => fake()->randomElement([
                    'Administrative Division',
                    'Finance Division',
                    'Records Division',
                    'Legislative Division'
                ]),
                'type' => 'staff',
                'bio' => fake()->sentence(12),
            ];
        }

        DB::table('officials')->insert($officials);
    }
}