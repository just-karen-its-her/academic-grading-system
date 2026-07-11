<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Nilai
 *
 * Model untuk tabel nilais. Menyimpan nilai tugas, UTS, UAS, nilai
 * akhir, dan grade milik seorang mahasiswa untuk satu mata kuliah.
 */
class Nilai extends Model
{
    /**
     * Nama tabel di database.
     * Opsional tapi aman: menegaskan ke Laravel bahwa tabelnya bernama 'nilais'.
     */
    protected $table = 'nilais';

    /**
     * Kolom yang boleh diisi secara mass assignment dari form (Security).
     */
    protected $fillable = [
        'mahasiswa_nim',
        'mata_kuliah_kode',
        'nilai_tugas',
        'nilai_uts',
        'nilai_uas',
        'nilai_akhir',
        'grade'
    ];

    /**
     * Relasi ke tabel mahasiswa.
     * Foreign key 'mahasiswa_nim' merujuk ke kolom 'nim' pada Mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_nim', 'nim');
    }

    /**
     * Relasi ke tabel mata kuliah.
     * Foreign key 'mata_kuliah_kode' merujuk ke kolom 'kode_mk' pada MataKuliah.
     */
    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_kode', 'kode_mk');
    }
}