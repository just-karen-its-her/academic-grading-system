<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

/**
 * MahasiswaController
 *
 * Menangani penampilan data mahasiswa.
 */
class MahasiswaController extends Controller
{
    /**
     * Menampilkan seluruh data mahasiswa.
     */
    public function index()
    {
        // Mengambil semua data dari tabel mahasiswas
        $mahasiswas = Mahasiswa::all();

        // Kirim variabel $mahasiswas ke view
        // Sesuaikan 'mahasiswa.index' dengan lokasi file blade Anda
        // (contoh: resources/views/mahasiswa/index.blade.php)
        return view('mahasiswa.index', compact('mahasiswas'));
    }
}