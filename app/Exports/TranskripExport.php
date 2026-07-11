<?php

namespace App\Exports;

use App\Models\Nilai;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

/**
 * TranskripExport
 *
 * Class export untuk menghasilkan file Excel transkrip nilai
 * milik mahasiswa yang sedang login (Auth::user()).
 */
class TranskripExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * Counter untuk kolom "No" pada setiap baris hasil export.
     */
    private $rowNumber = 0;

    /*
    |--------------------------------------------------------------------------
    | Query Data
    |--------------------------------------------------------------------------
    | Mengambil seluruh data nilai milik mahasiswa yang sedang login,
    | beserta relasi mata kuliah.
    */
    public function collection()
    {
        // Sesuaikan query dengan yang ada di Controller Anda
        $user = Auth::user();
        return Nilai::with('mataKuliah')
            ->where('mahasiswa_nim', $user->nomor_induk)
            ->get();
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
            $nilai->mataKuliah->semester ?? '-',
            $nilai->mataKuliah->kode_mk ?? '-',
            $nilai->mataKuliah->nama_mk ?? '-',
            $nilai->mataKuliah->sks ?? '-',
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
            'Semester',
            'Kode MK',
            'Mata Kuliah',
            'SKS',
            'Nilai Angka',
            'Grade'
        ];
    }
}