<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin
        User::firstOrCreate(
            ['email' => 'admin@perpustakaan.com'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );

        // 2. Akun Petugas
        User::firstOrCreate(
            ['email' => 'petugas@perpustakaan.com'],
            [
                'name'     => 'Petugas Perpustakaan',
                'password' => Hash::make('password123'),
                'role'     => 'petugas',
            ]
        );

        // 3. Akun Admin new
        User::firstOrCreate(
            ['email' => 'adminNew@perpustakaan.com'],
            [
                'name'     => 'Admin New Perpustakaan',
                'password' => Hash::make('password123'),
                'role'     => 'admin',
            ]
        );
    }
}
