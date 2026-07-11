<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mahasiswa
 *
 * Model untuk tabel mahasiswas. Menggunakan NIM sebagai primary key
 * (bukan auto-increment), sesuai kebutuhan penamaan akademik.
 */
class Mahasiswa extends Model
{
    /**
     * Nama tabel di database.
     * Opsional tapi aman: menegaskan ke Laravel bahwa tabelnya bernama 'mahasiswas'.
     */
    protected $table = 'mahasiswas';

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Primary Key Custom
    |--------------------------------------------------------------------------
    */
    protected $primaryKey = 'nim';
    public $incrementing = false; // NIM bukan angka berurutan otomatis (bukan auto-increment)
    protected $keyType = 'string'; // Pastikan NIM dibaca sebagai string

    /**
     * Kolom yang boleh diisi secara mass assignment dari form (Security).
     */
    protected $fillable = ['nim', 'nama', 'jurusan', 'angkatan'];

    /**
     * Relasi ke tabel nilai (dipakai untuk rekap transkrip).
     * Foreign key 'mahasiswa_nim' & owner key 'nim' harus ditulis eksplisit
     * agar Laravel tidak mencari 'mahasiswa_id' secara default.
     */
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class, 'mahasiswa_nim', 'nim');
    }
}