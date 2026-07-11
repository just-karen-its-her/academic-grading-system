<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * DatabaseSeeder
 *
 * Menjalankan seeder secara berurutan: tabel master (User, Mahasiswa,
 * MataKuliah) terlebih dahulu, baru diikuti tabel transaksi/relasi (Nilai)
 * agar foreign key terpenuhi.
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seeder aplikasi.
     */
    public function run(): void
    {
        $this->call([
                // Tabel master
            UserSeeder::class,
            MahasiswaSeeder::class,
            MataKuliahSeeder::class,

                // Tabel transaksi/relasi, dijalankan paling akhir
            NilaiSeeder::class,
        ]);
    }
}