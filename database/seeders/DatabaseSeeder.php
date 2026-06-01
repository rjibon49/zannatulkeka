<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ১. Super Admin (আপনার অ্যাকাউন্ট)
        User::factory()->create([
            'name' => 'Md. Raihan Hossain Jibon',
            'email' => 'rjibon49@gmail.com',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
        ]);

        // ২. Admin
        User::factory()->create([
            'name' => 'General Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // ৩. Contributor
        User::factory()->create([
            'name' => 'Content Writer',
            'email' => 'writer@example.com',
            'role' => 'contributor',
            'password' => Hash::make('password'),
        ]);
    }
}