<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::create([
            'name'         => 'admin',
            'display_name' => 'Administrator', // optional
            'description'  => 'User with access to everything', // optional
        ]);

        $manager = Role::create([
            'name'         => 'manager',
            'display_name' => 'Manager', // optional
            'description'  => 'User with access to manage system features', // optional
        ]);

        $hr = Role::create([
            'name'         => 'hr',
            'display_name' => 'HR Manager', // optional
            'description'  => 'User with access to HR and employees module', // optional
        ]);

        $accountant = Role::create([
            'name'         => 'accountant',
            'display_name' => 'Accountant', // optional
            'description'  => 'User with access to financial features', // optional
        ]);
    }
}