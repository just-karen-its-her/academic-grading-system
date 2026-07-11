@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>Dashboard</h1>
@stop

@section('content')

{{-- ================= 4 KOTAK STATISTIK ================= --}}
<div class="row">
    @php
        $stats = [
            ['color' => 'bg-info', 'icon' => 'fa-user-graduate', 'label' => 'Mahasiswa', 'value' => 30],
            ['color' => 'bg-success', 'icon' => 'fa-chalkboard-teacher', 'label' => 'Dosen', 'value' => 15],
            ['color' => 'bg-warning', 'icon' => 'fa-book', 'label' => 'Mata Kuliah', 'value' => 5],
            ['color' => 'bg-purple', 'icon' => 'fa-edit', 'label' => 'Total Nilai', 'value' => 350],
        ];
    @endphp

    @foreach ($stats as $stat)
        <div class="col-lg-3 col-6">
            <div class="small-box {{ $stat['color'] }}">
                <div class="inner">
                    <h3>{{ $stat['value'] }}</h3>
                    <p>{{ $stat['label'] }}</p>
                </div>
                <div class="icon">
                    <i class="fas {{ $stat['icon'] }}"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    @endforeach
</div>

{{-- ================= TABEL & PANEL INFORMASI ================= --}}
<div class="row">

    {{-- Tabel Rekap Nilai Terbaru --}}
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-clipboard-list mr-1"></i> Rekap Nilai Terbaru
                </h3>
                <div class="card-tools">
                    <span class="badge badge-primary">3 entri terbaru</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>

            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>No Absen</th>
                            <th>Mahasiswa</th>
                            <th>Mata Kuliah</th>
                            <th>Nilai Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Andi Pratama</td>
                            <td>Pemrograman Web</td>
                            <td>
                                <div class="progress progress-xs mb-1">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                                <span class="badge badge-success">25</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="card-footer clearfix">
                <a href="javascript:void(0)" class="btn btn-sm btn-primary float-left">
                    <i class="fas fa-list mr-1"></i> Lihat Semua
                </a>
            </div>
        </div>
    </div>

    {{-- Panel Informasi Sistem --}}
    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-header text-muted border-bottom-0">
                <i class="fas fa-info-circle mr-1"></i> Informasi Sistem
            </div>

            <div class="card-body pt-0 mt-3">
                <div class="alert alert-info">
                    <i class="icon fas fa-graduation-cap"></i>
                    Selamat datang di Sistem Penilaian! <br>
                    Kelola data akademik dengan mudah dan efisien.
                </div>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <i class="fas fa-calendar-alt mr-1"></i> <b>Tanggal</b>
                        <a class="float-right">{{ date('d M Y') }}</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-code-branch mr-1"></i> <b>Versi</b>
                        <a class="float-right">1.0</a>
                    </li>
                    <li class="list-group-item">
                        <i class="fas fa-server mr-1"></i> <b>Status Server</b>
                        <span class="float-right badge badge-success">Online</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>

@stop