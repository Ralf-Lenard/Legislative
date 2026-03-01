<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🔵 ADMIN USER
        DB::table('users')->insert([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'usertype' => 'super_admin',
            'address' => 'Municipal Hall',
            'birthdate' => '1990-01-01',
            'contact_number' => '09123456789',
            'profile_photo' => null,
            'status' => 'active',
            'password' => Hash::make('pass1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🟢 STAFF USER
        DB::table('users')->insert([
            'name' => 'Staff User',
            'email' => 'staff@example.com',
            'email_verified_at' => now(),
            'usertype' => 'admin',
            'address' => 'Municipal Office',
            'birthdate' => '1995-05-10',
            'contact_number' => '09987654321',
            'profile_photo' => null,
            'status' => 'active',
            'password' => Hash::make('pass1234'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 🧑‍🤝‍🧑 48 REGULAR USERS
        for ($i = 1; $i <= 48; $i++) {
            DB::table('users')->insert([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'email_verified_at' => now(),
                'usertype' => 'user',
                'address' => fake()->address(),
                'birthdate' => fake()->date(),
                'contact_number' => fake()->phoneNumber(),
                'profile_photo' => null,
                'status' => fake()->randomElement(['active', 'active', 'active', 'inactive']),
                'password' => Hash::make('pass1234'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}