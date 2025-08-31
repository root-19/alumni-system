<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the AdminSeeder first
        $this->call([
            AdminSeeder::class,
        ]);

        // Create a test regular user
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
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
    }
}
