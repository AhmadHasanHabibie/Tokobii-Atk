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
                'password' => Hash::make('VvZzAdmin87:.'),
                'role' => 'admin',
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
                'password' => Hash::make('VvZzSuperadmin97:.'),
                'role' => 'superadmin',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}