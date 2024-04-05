<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate();

        User::create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => 'admin@123',
        ]);

        User::create([
            'username' => 'admin1',
            'email' => 'admin1@gmail.com',
            'password' => 'admin@123',
        ]);
    }
}