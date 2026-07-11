@extends('adminlte::page')

@section('title', 'Transkrip Akademik')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Transkrip Akademik</h1>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-info">

            {{-- ── Header Card: Judul & Tombol Export ────────────────────── --}}
            <div class="card-header">
                <h3 class="card-title mt-1">Data Transkrip Mahasiswa</h3>
                <div class="card-tools">
                    <a href="{{ route('transkrip.pdf') }}" target="_blank" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </a>
                    <a href="{{ route('transkrip.excel') }}" target="_blank" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>

            {{-- ── Profil Mahasiswa ──────────────────────────────────────────
            Fallback ke Auth::user() jika $profil kosong, sehingga tetap
            tampil wajar walau data Mahasiswa belum lengkap di database. --}}
            <div class="card-body bg-light border-bottom py-3">
                <div class="row">
                    <div class="col-sm-4">
                        <span class="text-muted">NIM:</span><br>
                        <b style="font-size: 1.1rem;">{{ $profil->nim ?? Auth::user()->nomor_induk }}</b>
                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted">Nama Lengkap:</span><br>
                        <b style="font-size: 1.1rem;">{{ $profil->nama ?? Auth::user()->name }}</b>
                    </div>
                    <div class="col-sm-4">
                        <span class="text-muted">Program Studi:</span><br>
                        <b style="font-size: 1.1rem;">{{ $profil->jurusan ?? 'Tidak Diketahui' }}</b>
                    </div>
                </div>
            </div>

            {{-- ── Tabel Transkrip Nilai ────────────────────────────────────
            Menampilkan seluruh mata kuliah yang sudah dinilai, lengkap
            dengan SKS dan grade per mata kuliah. --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover m-0">
                        <thead class="bg-white">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th class="text-center" style="width: 10%">Semester</th>
                                <th class="text-center" style="width: 15%">Kode MK</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center" style="width: 8%">SKS</th>
                                <th class="text-center" style="width: 10%">Nilai Angka</th>
                                <th class="text-center" style="width: 8%">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilais as $index => $nilai)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="text-center">{{ $nilai->mataKuliah->semester ?? '-' }}</td>
                                    <td class="text-center">{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
                                    <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                                    <td class="text-center">{{ $nilai->mataKuliah->sks ?? '-' }}</td>
                                    <td class="text-center">{{ number_format($nilai->nilai_akhir, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $nilai->grade }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Belum ada data nilai untuk transkrip.</td>
                                </tr>
                            @endforelse
                        </tbody>

                        {{-- ── Total SKS Kumulatif & IPK ──────────────────────── --}}
                        <tfoot class="bg-light">
                            <tr>
                                <th colspan="4" class="text-right align-middle">Total SKS Kumulatif</th>
                                <th class="text-center align-middle" style="font-size: 1.1rem;">{{ $total_sks ?? 0 }}
                                </th>
                                <th class="text-right align-middle">IPK</th>
                                <th class="text-center align-middle text-primary" style="font-size: 1.1rem;">
                                    {{ number_format($ipk, 2) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@stop