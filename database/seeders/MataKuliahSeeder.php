<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MataKuliahSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('mata_kuliahs')->insert([
            [
                'kode_mk' => 'PWL01',
                'nama_mk' => 'Pemrograman Web Lanjut',
                'sks' => 3,
                'semester' => 1,
                'dosen_nip' => '22345678',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'BD02',
                'nama_mk' => 'Basis Data',
                'sks' => 3,
                'semester' => 2,
                'dosen_nip' => '22345679',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'PBO03',
                'nama_mk' => 'Pemrograman Berorientasi Objek',
                'sks' => 3,
                'semester' => 3,
                'dosen_nip' => '22345680',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'RPL04',
                'nama_mk' => 'Rekayasa Perangkat Lunak',
                'sks' => 3,
                'semester' => 4,
                'dosen_nip' => '22345681',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode_mk' => 'AI05',
                'nama_mk' => 'Kecerdasan Buatan',
                'sks' => 3,
                'semester' => 5,
                'dosen_nip' => '22345682',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}