<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        $admin = User::create([
            'name' => 'الادمن',
            'email' => 'info@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '01012345678',
            'address' => 'القاهرة',
        ]);
        $admin->addRole('admin');

        // Manager user
        $manager = User::create([
            'name' => 'المدير',
            'email' => 'manager@gmail.com',
            'password' => Hash::make('12345678'),
            'phone' => '01023456789',
            'address' => 'الإسكندرية',
        ]);
        $manager->addRole('manager');


    }
}
