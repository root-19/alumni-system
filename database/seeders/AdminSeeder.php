<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@alumni.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'middle_name' => null,
            'suffix' => null,
            'year_graduated' => null,
            'program' => null,
            'gender' => null,
            'status' => 'active',
            'contact_number' => null,
            'address' => null,
            'profile_image_path' => null,
        ]);

        // Create a second admin user for backup
        User::create([
            'name' => 'System Administrator',
            'email' => 'system@alumni.com',
            'password' => Hash::make('system123'),
            'role' => 'admin',
            'middle_name' => null,
            'suffix' => null,
            'year_graduated' => null,
            'program' => null,
            'gender' => null,
            'status' => 'active',
            'contact_number' => null,
            'address' => null,
            'profile_image_path' => null,
        ]);

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: admin@alumni.com, Password: admin123');
        $this->command->info('Email: system@alumni.com, Password: system123');
    }
}
