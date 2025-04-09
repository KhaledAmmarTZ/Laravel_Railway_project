<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            [
                'name' => 'Admin One',
                'email' => 'admin1@example.com',
                'date_of_birth' => '1990-01-01',
                'role' => 'admin',
                'phoneNumber' => '1234567890',
                'place' => 'City One',
                'admin_nid' => 'NID123456',
                'admin_image' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Two',
                'email' => 'admin2@example.com',
                'date_of_birth' => '1992-02-02',
                'role' => 'admin',
                'phoneNumber' => '0987654321',
                'place' => 'City Two',
                'admin_nid' => 'NID654321',
                'admin_image' => null,
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
