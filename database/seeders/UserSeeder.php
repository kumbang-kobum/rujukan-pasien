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

        // Super admin platform
        User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin Platform',
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUPER_ADMIN,
                'rumah_sakit_id' => null,
            ]
        );

        // Admin rumah sakit
        User::updateOrCreate(
            ['email' => 'admin.rsa@example.com'],
            [
                'name' => 'Admin RSA',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN_RS,
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin.rsb@example.com'],
            [
                'name' => 'Admin RSB',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN_RS,
                'rumah_sakit_id' => $rsb->id
            ]
        );

        // Dokter
        User::updateOrCreate(
            ['email' => 'dokter.rsa@example.com'],
            [
                'name' => 'dr. RSA',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOKTER,
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'dokter.rsb@example.com'],
            [
                'name' => 'dr. RSB',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DOKTER,
                'rumah_sakit_id' => $rsb->id
            ]
        );

        // Petugas
        User::updateOrCreate(
            ['email' => 'perawat.rsa@example.com'],
            [
                'name' => 'Petugas RSA',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PETUGAS,
                'rumah_sakit_id' => $rsa->id
            ]
        );

        User::updateOrCreate(
            ['email' => 'perawat.rsb@example.com'],
            [
                'name' => 'Petugas RSB',
                'password' => Hash::make('password'),
                'role' => User::ROLE_PETUGAS,
                'rumah_sakit_id' => $rsb->id
            ]
        );
    }
}
