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
        // Create admin user with adminalumni@gmail.com
        User::updateOrCreate(
            ['email' => 'adminalumni@gmail.com'],
            [
                'name' => 'Admin Alumni',
                'email' => 'adminalumni@gmail.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_alumni' => false,
                'middle_name' => null,
                'suffix' => null,
                'year_graduated' => null,
                'program' => null,
                'gender' => null,
                'status' => 'active',
                'contact_number' => null,
                'address' => null,
                'profile_image_path' => null,
            ]
        );

        // Create admin user
        User::updateOrCreate(
            ['email' => 'admin@alumni.com'],
            [
                'name' => 'Admin User',
                'email' => 'admin@alumni.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'is_alumni' => false,
                'middle_name' => null,
                'suffix' => null,
                'year_graduated' => null,
                'program' => null,
                'gender' => null,
                'status' => 'active',
                'contact_number' => null,
                'address' => null,
                'profile_image_path' => null,
            ]
        );

        // Create a second admin user for backup
        User::updateOrCreate(
            ['email' => 'system@alumni.com'],
            [
                'name' => 'System Administrator',
                'email' => 'system@alumni.com',
                'password' => Hash::make('system123'),
                'role' => 'admin',
                'is_alumni' => false,
                'middle_name' => null,
                'suffix' => null,
                'year_graduated' => null,
                'program' => null,
                'gender' => null,
                'status' => 'active',
                'contact_number' => null,
                'address' => null,
                'profile_image_path' => null,
            ]
        );

        $this->command->info('Admin users created successfully!');
        $this->command->info('Email: adminalumni@gmail.com, Password: admin123');
        $this->command->info('Email: admin@alumni.com, Password: admin123');
        $this->command->info('Email: system@alumni.com, Password: system123');
    }
}
