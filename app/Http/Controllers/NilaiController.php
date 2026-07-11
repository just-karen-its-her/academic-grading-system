<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapNilaiExport;
use App\Exports\TranskripExport;

use App\Models\Nilai;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Wajib di-import untuk membaca session user (Dosen)

/**
 * NilaiController
 *
 * Mengelola data nilai mahasiswa: CRUD nilai per dosen, serta
 * export rekap nilai dan transkrip akademik ke PDF/Excel.
 */
class NilaiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Bobot Penilaian
    |--------------------------------------------------------------------------
    | Persentase kontribusi masing-masing komponen terhadap nilai akhir.
    */
    const BOBOT_TUGAS = 0.30;
    const BOBOT_UTS = 0.30;
    const BOBOT_UAS = 0.40;

    /*
    |--------------------------------------------------------------------------
    | Helpers Private
    |--------------------------------------------------------------------------
    | Logika bantu yang dipakai bersama oleh method store() dan update().
    */

    /**
     * Menghitung nilai akhir berdasarkan bobot Tugas, UTS, dan UAS.
     */
    private function hitungNilaiAkhir(float $tugas, float $uts, float $uas): float
    {
        return ($tugas * self::BOBOT_TUGAS)
            + ($uts * self::BOBOT_UTS)
            + ($uas * self::BOBOT_UAS);
    }

    /**
     * Menentukan huruf grade (A-E) berdasarkan nilai akhir.
     */
    private function tentukanGrade(float $nilaiAkhir): string
    {
        return match (true) {
            $nilaiAkhir >= 85 => 'A',
            $nilaiAkhir >= 70 => 'B',
            $nilaiAkhir >= 55 => 'C',
            $nilaiAkhir >= 40 => 'D',
            default => 'E',
        };
    }

    /**
     * Aturan validasi untuk input form tambah/edit nilai.
     * Nama kolom disesuaikan dengan database (mahasiswa_nim, mata_kuliah_kode).
     */
    private function validasiRules(): array
    {
        return [
            'mahasiswa_nim' => 'required|exists:mahasiswas,nim',
            'mata_kuliah_kode' => 'required|exists:mata_kuliahs,kode_mk',
            'nilai_tugas' => 'required|numeric|min:0|max:100',
            'nilai_uts' => 'required|numeric|min:0|max:100',
            'nilai_uas' => 'required|numeric|min:0|max:100',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD
    |--------------------------------------------------------------------------
    */

    /**
     * Menampilkan daftar nilai, dibatasi hanya untuk mata kuliah
     * yang diajarkan oleh dosen yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        $nilais = Nilai::with(['mahasiswa', 'mataKuliah'])
            ->whereHas('mataKuliah', function ($query) use ($user) {
                // Mencocokkan kolom dosen_nip dengan NIP dosen yang login
                $query->where('dosen_nip', $user->nomor_induk);
            })
            ->latest()
            ->paginate(10);

        return view('nilai.index', compact('nilais'));
    }

    /**
     * Menampilkan form tambah nilai. Dropdown mata kuliah hanya
     * menampilkan mata kuliah milik dosen yang sedang login.
     */
    public function create()
    {
        $user = Auth::user();

        $mahasiswas = Mahasiswa::orderBy('nama')->get();

        $mataKuliahs = MataKuliah::where('dosen_nip', $user->nomor_induk)
            ->orderBy('nama_mk')
            ->get();

        return view('nilai.create', compact('mahasiswas', 'mataKuliahs'));
    }

    /**
     * Menyimpan data nilai baru, sekaligus menghitung nilai akhir dan grade.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->validasiRules());

        $nilaiAkhir = $this->hitungNilaiAkhir(
            $validated['nilai_tugas'],
            $validated['nilai_uts'],
            $validated['nilai_uas']
        );

        Nilai::create([
            ...$validated,
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade' => $this->tentukanGrade($nilaiAkhir),
        ]);

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu data nilai beserta relasi mahasiswa & mata kuliah.
     */
    public function show(Nilai $nilai)
    {
        $nilai->load(['mahasiswa', 'mataKuliah']);

        return view('nilai.show', compact('nilai'));
    }

    /**
     * Menampilkan form edit nilai. Dropdown mata kuliah hanya
     * menampilkan mata kuliah milik dosen yang sedang login.
     */
    public function edit(Nilai $nilai)
    {
        $user = Auth::user();

        $mahasiswas = Mahasiswa::orderBy('nama')->get();

        $mataKuliahs = MataKuliah::where('dosen_nip', $user->nomor_induk)
            ->orderBy('nama_mk')
            ->get();

        return view('nilai.edit', compact('nilai', 'mahasiswas', 'mataKuliahs'));
    }

    /**
     * Memperbarui data nilai, sekaligus menghitung ulang nilai akhir dan grade.
     */
    public function update(Request $request, Nilai $nilai)
    {
        $validated = $request->validate($this->validasiRules());

        $nilaiAkhir = $this->hitungNilaiAkhir(
            $validated['nilai_tugas'],
            $validated['nilai_uts'],
            $validated['nilai_uas']
        );

        $nilai->update([
            ...$validated,
            'nilai_akhir' => round($nilaiAkhir, 2),
            'grade' => $this->tentukanGrade($nilaiAkhir),
        ]);

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    /**
     * Menghapus data nilai.
     */
    public function destroy(Nilai $nilai)
    {
        $nilai->delete();

        return redirect()
            ->route('nilai.index')
            ->with('success', 'Nilai berhasil dihapus.');
    }

    /*
    |--------------------------------------------------------------------------
    | Export Rekap Nilai
    |--------------------------------------------------------------------------
    | Mengunduh rekap nilai (seluruh mahasiswa) dalam format PDF atau Excel,
    | dengan filter opsional semester dan kode mata kuliah.
    */

    /**
     * Export rekap nilai ke PDF.
     */
    public function exportPdf(Request $request)
    {
        $query = Nilai::with(['mahasiswa', 'mataKuliah']);

        if ($request->filled('semester')) {
            $query->whereHas('mataKuliah', function ($q) use ($request) {
                $q->where('semester', $request->semester);
            });
        }
        if ($request->filled('kode_mk')) {
            // Ubah kode_mk menjadi mata_kuliah_kode
            $query->where('mata_kuliah_kode', $request->kode_mk);
        }

        $nilais = $query->get();

        // Load view khusus PDF (buat file resources/views/rekap_nilai_pdf.blade.php nanti)
        $pdf = Pdf::loadView('rekap-nilai.pdf', compact('nilais'));

        return $pdf->download('rekap-nilai-mahasiswa.pdf');
    }

    /**
     * Export rekap nilai ke Excel (menggunakan RekapNilaiExport).
     * Pastikan class export sudah dibuat via: php artisan make:export RekapNilaiExport
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new RekapNilaiExport($request), 'rekap-nilai.xlsx');
    }

    /*
    |--------------------------------------------------------------------------
    | Export Transkrip Akademik
    |--------------------------------------------------------------------------
    | Mengunduh transkrip akademik milik mahasiswa yang sedang login,
    | lengkap dengan perhitungan IPK, dalam format PDF atau Excel.
    */

    /**
     * Export transkrip akademik mahasiswa yang sedang login ke PDF,
     * termasuk perhitungan total SKS dan IPK.
     */
    public function exportTranskripPdf()
    {
        $user = Auth::user();

        // Ambil data profil dan nilai
        $profil = Mahasiswa::where('nim', $user->nomor_induk)->first();
        $nilais = Nilai::with('mataKuliah')->where('mahasiswa_nim', $user->nomor_induk)->get();
        $total_sks = $nilais->sum('mataKuliah.sks');

        // ─── Perhitungan IPK ────────────────────────────────────────
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
        // ────────────────────────────────────────────────────────────

        $pdf = Pdf::loadView('transkrip.pdf', compact('profil', 'nilais', 'total_sks', 'ipk'));

        return $pdf->download('transkrip-akademik.pdf');
    }

    /**
     * Export transkrip akademik mahasiswa yang sedang login ke Excel
     * (menggunakan TranskripExport).
     * Pastikan class export sudah dibuat via: php artisan make:export TranskripExport
     */
    public function exportTranskripExcel()
    {
        return Excel::download(new TranskripExport(), 'transkrip-akademik.xlsx');
    }
}