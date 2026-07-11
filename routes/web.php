<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\NilaiController;
use App\Http\Controllers\AuthController;
use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Http\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Route untuk User yang Belum Login (Guest)
|--------------------------------------------------------------------------
*/
Route::middleware(['guest'])->group(function () {
    // Halaman login & prosesnya
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'prosesLogin'])->name('login.proses');
});

/*
|--------------------------------------------------------------------------
| Route yang Wajib Login (Auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ─── Halaman Utama & Logout ───────────────────────────────────────────
    Route::get('/', function () {
        return view('dashboard');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


    // ─── Data Akademik (Bisa Diakses Dosen & Mahasiswa) ───────────────────
    // Hanya route Mahasiswa yang ada di sini karena bisa diakses secara umum oleh user login
    Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('mahasiswa.index');


    /*
    |--------------------------------------------------------------------------
    | Khusus Dosen
    |--------------------------------------------------------------------------
    */
    Route::middleware([RoleMiddleware::class . ':dosen'])->group(function () {

        // Dosen memegang penuh akses CRUD (Create, Read, Update, Delete) untuk data Nilai
        Route::resource('nilai', NilaiController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Khusus Mahasiswa
    |--------------------------------------------------------------------------
    */
    Route::middleware([RoleMiddleware::class . ':mahasiswa'])->group(function () {

        // ─── Data Mata Kuliah ──────────────────────────────────────────────
        // Ditempatkan di sini agar jika dosen mencoba akses URL /matakuliah, akan ditolak sistem
        Route::get('/matakuliah', [MataKuliahController::class, 'index'])->name('matakuliah.index');

        // ─── Rekap Nilai ─────────────────────────────────────────────────────
        Route::get('/rekap-nilai', function (Request $request) {
            $user = Auth::user();

            // Query dasar: relasi ditarik, difilter khusus untuk NIM mahasiswa yang sedang login
            $query = Nilai::with(['mataKuliah', 'mahasiswa'])
                ->where('mahasiswa_nim', $user->nomor_induk);

            // Filter berdasarkan Semester dari form dropdown
            if ($request->filled('semester')) {
                $query->whereHas('mataKuliah', function ($q) use ($request) {
                    $q->where('semester', $request->semester);
                });
            }

            // Filter berdasarkan Mata Kuliah dari form dropdown
            if ($request->filled('kode_mk')) {
                $query->where('mata_kuliah_kode', $request->kode_mk);
            }

            $nilais = $query->get();

            // Ambil semua daftar mata kuliah untuk mengisi dropdown form pencarian
            $daftar_matkul = MataKuliah::all();

            return view('rekap-nilai.index', compact('nilais', 'daftar_matkul'));
        })->name('rekap-nilai.index');


        // ─── Transkrip Nilai ─────────────────────────────────────────────────
        Route::get('/transkrip', function () {
            $user = Auth::user();
            $nim = $user->nomor_induk;

            // Ambil data profil mahasiswa dan semua nilainya
            // Catatan: Jika Mahasiswa::find($nim) error/kosong, ganti dengan: Mahasiswa::where('nim', $nim)->first();
            $profil = Mahasiswa::find($nim);
            $nilais = Nilai::with('mataKuliah')->where('mahasiswa_nim', $nim)->get();

            // Hitung total SKS yang sudah diambil
            $total_sks = $nilais->sum(function ($nilai) {
                return $nilai->mataKuliah->sks ?? 0;
            });

            // ─── Perhitungan IPK ───────────────────────────────────────────
            $total_mutu = 0;
            foreach ($nilais as $n) {
                $sks = $n->mataKuliah->sks ?? 0;
                $bobot = match ($n->grade) {
                    'A' => 4,
                    'B' => 3,
                    'C' => 2,
                    'D' => 1,
                    default => 0,
                };
                $total_mutu += ($sks * $bobot);
            }
            $ipk = $total_sks > 0 ? ($total_mutu / $total_sks) : 0;
            // ─────────────────────────────────────────────────────────────────

            return view('transkrip.index', compact('profil', 'nilais', 'total_sks', 'ipk'));
        })->name('transkrip.index');

    });

    // ─── Export Rekap Nilai (PDF & Excel) ─────────────────────────────────
    Route::get('/rekap-nilai/pdf', [NilaiController::class, 'exportPdf'])->name('rekap-nilai.pdf');
    Route::get('/rekap-nilai/excel', [NilaiController::class, 'exportExcel'])->name('rekap-nilai.excel');

    // ─── Export Transkrip (PDF & Excel) ───────────────────────────────────
    Route::get('/transkrip/pdf', [NilaiController::class, 'exportTranskripPdf'])->name('transkrip.pdf');
    Route::get('/transkrip/excel', [NilaiController::class, 'exportTranskripExcel'])->name('transkrip.excel');

});