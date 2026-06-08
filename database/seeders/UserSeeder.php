<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Admin SIPERTA',
            'email'    => 'admin@siperta.com',
            'password' => Hash::make('password123'),
            'role'     => 'admin',
            'status'   => 'aktif',
        ]);

        // Petani
        User::create([
            'name'        => 'Budi Petani',
            'email'       => 'petani@siperta.com',
            'password'    => Hash::make('password123'),
            'role'        => 'petani',
            'no_hp'       => '081234567890',
            'alamat'      => 'Sleman, Yogyakarta',
            'no_rekening' => '1234567890',
            'status'      => 'aktif',
        ]);

        // Ahli
        User::create([
            'name'    => 'Dr. Sari Ahli',
            'email'   => 'ahli@siperta.com',
            'password'=> Hash::make('password123'),
            'role'    => 'ahli',
            'no_hp'   => '082345678901',
            'bidang'  => 'Agronomi',
            'status'  => 'aktif',
        ]);

        // Pedagang
        User::create([
            'name'    => 'Toko Sayur Maju',
            'email'   => 'pedagang@siperta.com',
            'password'=> Hash::make('password123'),
            'role'    => 'pedagang',
            'no_hp'   => '083456789012',
            'alamat'  => 'Bantul, Yogyakarta',
            'status'  => 'aktif',
        ]);
    }
}