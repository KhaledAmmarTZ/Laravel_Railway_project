<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::connection('mysql1')->table('users')->insert([
            [
                'name' => 'User One',
                'email' => 'user1@example.com',
                'date_of_birth' => '1990-01-01',
                'role' => 'user',
                'phoneNumber' => '1234567890',
                'place' => 'City One',
                'user_nid' => 'NID123456',
                'user_image' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User Two',
                'email' => 'user2@example.com',
                'date_of_birth' => '1992-02-02',
                'role' => 'user',
                'phoneNumber' => '0987654321',
                'place' => 'City Two',
                'user_nid' => 'NID654321',
                'user_image' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}