<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mahasiswas')->insert([
            [
                'nim' => '1062575',
                'nama' => 'Muhamad Azrir',
                'jurusan' => 'Teknologi Rekayasa Perangkat Lunak',
                'angkatan' => '2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1062579',
                'nama' => 'Muhamad Salman',
                'jurusan' => 'Teknologi Rekayasa Perangkat Lunak',
                'angkatan' => '2025',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nim' => '1062587',
                'nama' => 'Syifa Nailatur Rahma',
                'jurusan' => 'Teknologi Rekayasa Perangkat Lunak',
                'angkatan' => '2025',
                'created_at' => now(),
                'updated_at' => now(),
            ]

        ]);
    }
}