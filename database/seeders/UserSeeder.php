<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\RumahSakit;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan ada 2 RS
        $rsa = RumahSakit::firstOrCreate(
            ['nama' => 'RS A'],
            ['alamat' => 'Jl. Contoh No.1, Kota A', 'telepon' => '021-1234567']
        );
        $rsb = RumahSakit::firstOrCreate(
            ['nama' => 'RS B'],
            ['alamat' => 'Jl. Contoh No.2, Kota B', 'telepon' => '021-7654321']
        );

        // Admin
        User::updateOrCreate(
            ['email' => 'admin.rsa@example.com'],
            [
                'name' => 'Admin RSA',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin.rsb@example.com'],
            [
                'name' => 'Admin RSB',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'rumah_sakit_id' => $rsb->id
            ]
        );

        // Dokter
        User::updateOrCreate(
            ['email' => 'dokter.rsa@example.com'],
            [
                'name' => 'dr. RSA',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'dokter.rsb@example.com'],
            [
                'name' => 'dr. RSB',
                'password' => Hash::make('password'),
                'role' => 'dokter',
                'rumah_sakit_id' => $rsb->id
            ]
        );

        // Perawat
        User::updateOrCreate(
            ['email' => 'perawat.rsa@example.com'],
            [
                'name' => 'Perawat RSA',
                'password' => Hash::make('password'),
                'role' => 'perawat',
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'perawat.rsb@example.com'],
            [
                'name' => 'Perawat RSB',
                'password' => Hash::make('password'),
                'role' => 'perawat',
                'rumah_sakit_id' => $rsb->id
            ]
        );
    }
}