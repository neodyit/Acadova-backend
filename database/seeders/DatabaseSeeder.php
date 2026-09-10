<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with initial Student and Faculty accounts.
     */
    public function run(): void
    {
        // Sample Student Account
        User::updateOrCreate(
            ['email' => 'student@neodyit.in'],
            [
                'name' => 'Mayank Tiwari',
                'role' => 'student',
                'roll_number' => '2546167',
                'password' => Hash::make('Mayank@881'),
            ]
        );

        // Sample Faculty Account
        User::updateOrCreate(
            ['email' => 'faculty@neodyit.in'],
            [
                'name' => 'Dr. Aman',
                'role' => 'faculty',
                'faculty_id' => 'J1234',
                'department' => 'Computer Science',
                'password' => Hash::make('Mayank@881'),
            ]
        );

        // Admin Account
        User::updateOrCreate(
            ['email' => 'mayank@neodyit.in'],
            [
                'name' => 'Mayank Admin',
                'role' => 'admin',
                'password' => Hash::make('Mayank123'),
            ]
        );
    }
}
