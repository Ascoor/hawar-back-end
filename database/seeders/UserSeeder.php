<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Clear existing users (optional)
        DB::table('users')->truncate();

        // Create the user
        DB::table('users')->insert([
            'name' => 'Askar', // You can change the name here
            'email' => 'a@a.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
