<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Create an admin user
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // Create a technician user
        User::firstOrCreate(
            ['email' => 'tech@example.com'],
            [
                'name' => 'Technician',
                'password' => bcrypt('password'),
                'role' => 'technician',
            ]
        );

        // Create a rider user
        User::firstOrCreate(
            ['email' => 'rider@example.com'],
            [
                'name' => 'Rider',
                'password' => bcrypt('password'),
                'role' => 'rider',
            ]
        );
    }
}
