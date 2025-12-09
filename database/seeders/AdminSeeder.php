<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'firstname' => 'Default',
            'lastname' => 'Administrator',
            'username' => 'admin',
            'password' => Hash::make('admin@123')
        ]);
    }
}