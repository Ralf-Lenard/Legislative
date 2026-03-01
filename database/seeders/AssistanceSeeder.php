<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Assistance;
use Faker\Factory as Faker;

class AssistanceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        $barangays = [
            'Poblacion',
            'San Nicolas',
            'San Jose',
            'Sto. Niño',
            'Alfonso',
            'Bagong Bayan',
            'Maligaya',
            'San Isidro',
            'Sta. Cruz',
            'Pinagbarilan'
        ];

        $schools = [
            'Bulacan State University',
            'UP Diliman',
            'National University',
            'FEU Manila',
            'UST Manila',
            'Polytechnic University of the Philippines',
            'La Consolacion University',
            'Centro Escolar University'
        ];

        // ✅ 20 Medical
        for ($i = 0; $i < 20; $i++) {
            Assistance::create([
                'type' => 'medical',
                'full_name' => strtoupper($faker->lastName . ', ' . $faker->firstName . ' ' . $faker->randomLetter . '.'),
                'barangay' => $faker->randomElement($barangays),
                'school' => null,
            ]);
        }

        // ✅ 15 Legal
        for ($i = 0; $i < 15; $i++) {
            Assistance::create([
                'type' => 'legal',
                'full_name' => strtoupper($faker->lastName . ', ' . $faker->firstName . ' ' . $faker->randomLetter . '.'),
                'barangay' => $faker->randomElement($barangays),
                'school' => null,
            ]);
        }

        // ✅ 15 Scholar
        for ($i = 0; $i < 15; $i++) {
            Assistance::create([
                'type' => 'scholar',
                'full_name' => strtoupper($faker->lastName . ', ' . $faker->firstName . ' ' . $faker->randomLetter . '.'),
                'barangay' => null,
                'school' => $faker->randomElement($schools),
            ]);
        }
    }
}