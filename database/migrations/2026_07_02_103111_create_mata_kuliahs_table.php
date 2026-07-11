<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            // PERBAIKAN 1: Jadikan kode_mk sebagai Primary Key (Hapus $table->id())
            $table->string('kode_mk', 20)->primary();

            $table->string('nama_mk');
            $table->integer('sks');
            $table->integer('semester');

            // PERBAIKAN 2: Tambahkan kolom dosen_nip
            $table->string('dosen_nip');

            $table->timestamps();

            // PERBAIKAN 3 (Opsional tapi wajib untuk integritas data):
            // Buat relasi yang menghubungkan dosen_nip ke tabel users (akun dosen)
            $table->foreign('dosen_nip')->references('nomor_induk')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};