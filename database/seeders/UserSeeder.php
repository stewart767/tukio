<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Primary Super Admin
        User::updateOrCreate(
            ['email' => 'deangarlus@gmail.com'],
            [
                'name' => 'Dean Garlus',
                'password' => Hash::make('kingcankill'),
                'role' => 'super_admin',
                'phone' => '+255123456789',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // System Super Admin (Standard MyCard Admin)
        User::updateOrCreate(
            ['email' => 'admin@mycard.co.tz'],
            [
                'name' => 'Tukio 360 SuperAdmin',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'phone' => '+255700000001',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Event Admin
        User::updateOrCreate(
            ['email' => 'mycard@gmail.com'],
            [
                'name' => 'My Card',
                'password' => Hash::make('password'),
                'role' => 'event_admin',
                'phone' => '+255123456790',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Staff
        User::updateOrCreate(
            ['email' => 'staff@mycard.com'],
            [
                'name' => 'Event Staff',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'phone' => '+255123456791',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
