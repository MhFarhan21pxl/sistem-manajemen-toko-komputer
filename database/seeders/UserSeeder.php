<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Membuat akun Admin
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Membuat akun Kasir
        User::create([
            'name' => 'Kasir Utama',
            'email' => 'kasir@toko.com',
            'password' => Hash::make('password123'),
            'role' => 'kasir',
        ]);
    }
}