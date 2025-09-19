<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        User::firstOrCreate(
            ['email' => 'admin@timekeeper.com'],
            [
                'name' => 'Admin TimeKeeper',
                'email' => 'admin@timekeeper.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // Update existing users to have 'user' role if they don't have one
        User::whereNull('role')->orWhere('role', '')->update(['role' => 'user']);
    }
}
