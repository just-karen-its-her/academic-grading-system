<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

/**
 * MataKuliahController
 *
 * Menangani penampilan data mata kuliah.
 */
class MataKuliahController extends Controller
{
    /**
     * Menampilkan seluruh data mata kuliah.
     */
    public function index()
    {
        // Ambil semua data dari tabel mata_kuliahs
        $mata_kuliahs = MataKuliah::all();

        // Kirim data ke view (sesuaikan 'nama_folder.nama_file' dengan lokasi file blade Anda)
        return view('matakuliah.index', compact('mata_kuliahs'));
    }
}