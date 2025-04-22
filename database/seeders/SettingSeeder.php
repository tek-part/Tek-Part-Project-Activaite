<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('settings')->insert([
            'name' => 'اسم الشركة',
            'address' => '123 الشارع الرئيسي، المدينة، البلد',
            'description' => 'مرحبًا بكم في شركتنا!',
            'email' => 'info@example.com',
            'phone1' => '010-020-0340',
            'phone2' => '090-080-0760',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
