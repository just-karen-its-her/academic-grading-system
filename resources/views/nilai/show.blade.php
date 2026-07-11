@extends('adminlte::page')

@section('title', 'Detail Nilai')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Detail Nilai</h1>
    <div>
        <a href="{{ route('nilai.edit', $nilai->id) }}" class="btn btn-warning">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
        <a href="{{ route('nilai.index') }}" class="btn btn-default ml-1">
            <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
    </div>
</div>
@stop

@section('content')

{{-- Ringkasan --}}
<div class="row">

    @php
        $gradeColor = match ($nilai->grade) {
            'A' => 'success',
            'B' => 'info',
            'C' => 'warning',
            'D' => 'secondary',
            default => 'danger',
        };
    @endphp

    {{-- Nilai Akhir --}}
    <div class="col-md-6 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-primary elevation-1">
                <i class="fas fa-chart-bar"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Nilai Akhir</span>
                <span class="info-box-number">{{ number_format($nilai->nilai_akhir, 2) }}</span>
            </div>
        </div>
    </div>

    {{-- Grade --}}
    <div class="col-md-6 col-sm-6">
        <div class="info-box">
            <span class="info-box-icon bg-{{ $gradeColor }} elevation-1">
                <i class="fas fa-graduation-cap"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Grade</span>
                <span class="info-box-number">{{ $nilai->grade }}</span>
            </div>
        </div>
    </div>

</div>

{{-- Detail Card --}}
<div class="row">

    {{-- Informasi Mahasiswa --}}
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-1"></i> Informasi Mahasiswa
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="40%" class="pl-3">NIM</th>
                        <td>{{ $nilai->mahasiswa->nim ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Nama</th>
                        <td>{{ $nilai->mahasiswa->nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Program Studi</th>
                        <td>{{ $nilai->mahasiswa->jurusan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Angkatan</th>
                        <td>{{ $nilai->mahasiswa->angkatan ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Informasi Mata Kuliah --}}
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-book mr-1"></i> Informasi Mata Kuliah
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered mb-0">
                    <tr>
                        <th width="40%" class="pl-3">Kode</th>
                        <td>{{ $nilai->mataKuliah->kode_mk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Nama</th>
                        <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th class="pl-3">SKS</th>
                        <td>{{ $nilai->mataKuliah->sks ?? '-' }} SKS</td>
                    </tr>
                    <tr>
                        <th class="pl-3">Dosen</th>
                        <td>{{ $nilai->mataKuliah->dosen ?? '-' }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Rincian Nilai --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-calculator mr-1"></i> Rincian Perhitungan Nilai
        </h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered">
            <thead class="bg-light">
                <tr>
                    <th>Komponen</th>
                    <th class="text-center">Bobot</th>
                    <th class="text-center">Nilai Mentah</th>
                    <th class="text-center">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Tugas</td>
                    <td class="text-center">30%</td>
                    <td class="text-center">{{ number_format($nilai->nilai_tugas, 0) }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_tugas * 0.30, 2) }}</td>
                </tr>
                <tr>
                    <td>UTS</td>
                    <td class="text-center">30%</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uts, 0) }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uts * 0.30, 2) }}</td>
                </tr>
                <tr>
                    <td>UAS</td>
                    <td class="text-center">40%</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uas, 0) }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_uas * 0.40, 2) }}</td>
                </tr>
            </tbody>
            <tfoot class="font-weight-bold">
                <tr class="bg-light">
                    <td colspan="3" class="text-right">Nilai Akhir</td>
                    <td class="text-center">
                        <strong>{{ number_format($nilai->nilai_akhir, 2) }}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="text-right">Grade</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $gradeColor }} badge-pill px-3 py-2">
                            {{ $nilai->grade }}
                        </span>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <div class="card-footer text-muted small">
        <i class="fas fa-info-circle mr-1"></i>
        Nilai Akhir = (Tugas × 30%) + (UTS × 30%) + (UAS × 40%)
    </div>
</div>

@stop