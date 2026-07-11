<?php

namespace App\Exports;

use App\Models\Nilai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * RekapNilaiExport
 *
 * Class export untuk menghasilkan file Excel rekap nilai mahasiswa.
 * Mendukung filter berdasarkan semester dan kode mata kuliah yang
 * dikirim melalui request dari Controller.
 */
class RekapNilaiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Menyimpan instance Request yang berisi parameter filter
     * (semester, kode_mk) dari Controller.
     */
    protected $request;

    /**
     * Counter untuk kolom "No" pada setiap baris hasil export.
     */
    private $rowNumber = 0;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    | Menangkap request filter yang dikirim dari Controller.
    */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /*
    |--------------------------------------------------------------------------
    | Query Data
    |--------------------------------------------------------------------------
    | Mengambil data nilai dari database beserta relasi mahasiswa
    | dan mata kuliah, lalu menerapkan filter sesuai parameter request.
    */
    public function collection()
    {
        $query = Nilai::with(['mahasiswa', 'mataKuliah']);

        // Filter berdasarkan semester (melalui relasi mataKuliah)
        if ($this->request->filled('semester')) {
            $query->whereHas('mataKuliah', function ($q) {
                $q->where('semester', $this->request->semester);
            });
        }

        // Filter berdasarkan kode mata kuliah
        if ($this->request->filled('kode_mk')) {
            // Ubah kode_mk menjadi mata_kuliah_kode agar sesuai database
            $query->where('mata_kuliah_kode', $this->request->kode_mk);
        }

        return $query->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Mapping Baris
    |--------------------------------------------------------------------------
    | Memetakan setiap record hasil query menjadi satu baris data
    | pada file Excel, sesuai urutan kolom di method headings().
    */
    public function map($nilai): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $nilai->mahasiswa->nama ?? '-', // Gunakan Auth::user()->name jika login sbg mahasiswa
            $nilai->mataKuliah->semester ?? '-',
            $nilai->mataKuliah->nama_mk ?? '-',
            $nilai->nilai_tugas,
            $nilai->nilai_uts,
            $nilai->nilai_uas,
            number_format($nilai->nilai_akhir, 2),
            $nilai->grade,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Judul Kolom
    |--------------------------------------------------------------------------
    | Menentukan judul kolom (header) pada baris pertama file Excel.
    */
    public function headings(): array
    {
        return [
            'No',
            'Nama Mahasiswa',
            'Semester',
            'Mata Kuliah',
            'Tugas',
            'UTS',
            'UAS',
            'Nilai Akhir',
            'Grade'
        ];
    }
}