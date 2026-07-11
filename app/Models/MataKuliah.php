<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * MataKuliah
 *
 * Model untuk tabel mata_kuliahs. Menggunakan kode_mk sebagai
 * primary key (bukan auto-increment).
 */
class MataKuliah extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'mata_kuliahs';

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Primary Key Custom
    |--------------------------------------------------------------------------
    */
    protected $primaryKey = 'kode_mk';
    public $incrementing = false; // kode_mk bukan angka berurutan otomatis
    protected $keyType = 'string'; // Pastikan kode_mk dibaca sebagai string

    /**
     * Kolom yang tidak dilindungi dari mass assignment (semua kolom diizinkan).
     */
    protected $guarded = [];

    /**
     * Relasi ke tabel nilai.
     * Foreign key 'mata_kuliah_kode' & owner key 'kode_mk' ditulis eksplisit
     * agar Laravel tidak mencari 'mata_kuliah_id' secara default.
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class, 'mata_kuliah_kode', 'kode_mk');
    }
}