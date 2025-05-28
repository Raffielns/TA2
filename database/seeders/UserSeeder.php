<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 1, // misalnya 1 = admin
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Manager',
                'last_name' => 'User',
                'email' => 'manager@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 2, // misalnya 2 = manager
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Customer',
                'last_name' => 'User',
                'email' => 'customer@example.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'role' => 0, // misalnya 0 = customer
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
