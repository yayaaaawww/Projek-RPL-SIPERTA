<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // firstOrCreate: aman dijalankan berkali-kali (tidak bikin duplikat)
        User::firstOrCreate(
            ['email' => 'admin@siperta.com'],
            ['name' => 'Admin SIPERTA', 'password' => Hash::make('password123'), 'role' => 'admin', 'status' => 'aktif']
        );

        User::firstOrCreate(
            ['email' => 'petani@siperta.com'],
            ['name' => 'Budi Petani', 'password' => Hash::make('password123'), 'role' => 'petani',
             'no_hp' => '081234567890', 'alamat' => 'Sleman, Yogyakarta', 'no_rekening' => '1234567890', 'status' => 'aktif']
        );

        User::firstOrCreate(
            ['email' => 'ahli@siperta.com'],
            ['name' => 'Dr. Sari Ahli', 'password' => Hash::make('password123'), 'role' => 'ahli',
             'no_hp' => '082345678901', 'bidang' => 'Agronomi', 'status' => 'aktif']
        );

        User::firstOrCreate(
            ['email' => 'pedagang@siperta.com'],
            ['name' => 'Toko Sayur Maju', 'password' => Hash::make('password123'), 'role' => 'pedagang',
             'no_hp' => '083456789012', 'alamat' => 'Bantul, Yogyakarta', 'status' => 'aktif']
        );
    }
}
