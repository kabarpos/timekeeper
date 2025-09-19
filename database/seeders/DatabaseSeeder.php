<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call AdminUserSeeder to create admin user
        $this->call([
            AdminUserSeeder::class,
        ]);

        // Optional: Create additional test users for development
        if (app()->environment('local', 'testing')) {
            User::factory()->create([
                'name' => 'Staff',
                'email' => 'user@kirim.biz.id',
                'role' => 'user',
            ]);
        }
    }
}
