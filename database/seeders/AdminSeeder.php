<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@tokobii.test',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'owner@tokobii.test',
            ],
            [
                'name' => 'Owner Tokobii',
                'password' => Hash::make('Owner123!'),
                'role' => 'owner',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        User::updateOrCreate(
            [
                'email' => 'superadmin@tokobii.test',
            ],
            [
                'name' => 'IT Security Superadmin',
                'password' => Hash::make('Superadmin123!'),
                'role' => 'superadmin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}