<?php
// database/seeders/CreateAllRolesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateAllRolesSeeder extends Seeder
{
    public function run()
    {
        $roles = ['admin', 'technician', 'rider', 'staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
