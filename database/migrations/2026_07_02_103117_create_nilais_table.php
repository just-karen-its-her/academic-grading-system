<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel mahasiswas dan mata_kuliahs
            $table->string('mahasiswa_nim');
            $table->string('mata_kuliah_kode');

            // Komponen nilai
            $table->double('nilai_tugas')->default(0);
            $table->double('nilai_uts')->default(0);
            $table->double('nilai_uas')->default(0);
            $table->double('nilai_akhir')->default(0);
            $table->string('grade', 2)->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('mahasiswa_nim')->references('nim')->on('mahasiswas')->onDelete('cascade');
            $table->foreign('mata_kuliah_kode')->references('kode_mk')->on('mata_kuliahs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};