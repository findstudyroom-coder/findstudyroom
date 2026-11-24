<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Test user with password change required (first login)
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '9876543210',
            'password' => Hash::make('password'),
            'password_change_required' => true,
            'role' => 'user',
        ]);

        // Test user already changed password
        User::create([
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
            'phone' => '9876543211',
            'password' => Hash::make('password123'),
            'password_change_required' => false,
            'role' => 'user',
        ]);

        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'phone' => '9876543212',
            'password' => Hash::make('admin123'),
            'password_change_required' => false,
            'role' => 'admin',
        ]);

        // Create 5 more test users
        User::factory(5)->create();
    }
}
