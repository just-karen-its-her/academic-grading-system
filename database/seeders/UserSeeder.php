<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        // 1. Bikin Akun Dosen
        User::create([
            'name' => 'Ibu Dosen 1',
            'nomor_induk' => '22345678',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Ibu Dosen 2',
            'nomor_induk' => '22345679',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Ibu Dosen 3',
            'nomor_induk' => '22345680',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Ibu Dosen 4',
            'nomor_induk' => '22345681',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Ibu Dosen 5',
            'nomor_induk' => '22345682',
            'role' => 'dosen',
            'password' => Hash::make('password123'),
        ]);

        // 2. Bikin Akun Mahasiswa
        User::create([
            'name' => 'Muhamad Azrir',
            'nomor_induk' => '1062575', // Anggap ini NIM
            'role' => 'mahasiswa',
            'password' => Hash::make('password123')
        ]);
        User::create([
            'name' => 'Muhamhad Salman',
            'nomor_induk' => '1062579', // Anggap ini NIM
            'role' => 'mahasiswa',
            'password' => Hash::make('password123')
        ]);
        User::create([
            'name' => 'Syifa Nailatur Rahma',
            'nomor_induk' => '1062587', // Anggap ini NIM
            'role' => 'mahasiswa',
            'password' => Hash::make('password123')
        ]);
    }
}
