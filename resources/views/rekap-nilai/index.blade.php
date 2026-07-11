@extends('adminlte::page')

@section('title', 'Rekap Nilai')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Rekap Nilai</h1>
    </div>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">

        {{-- ── Form Filter (Semester & Mata Kuliah) ─────────────────────────
        Form GET agar filter tersimpan di query string, sehingga bisa
        dipakai ulang oleh tombol Cetak PDF & Export Excel di bawah
        (via request()->all()). --}}
        <div class="card-body py-3">
            <form method="GET" action="{{ route('rekap-nilai.index') }}" class="form-inline">

                <label class="mr-sm-2" for="filterSemester">Semester:</label>
                <select class="form-control mb-2 mb-sm-0 mr-sm-3" name="semester" id="filterSemester"
                    style="min-width: 150px;">
                    <option value="">Semua Semester</option>
                    <option value="1" {{ request('semester') == '1' ? 'selected' : '' }}>Semester 1</option>
                    <option value="2" {{ request('semester') == '2' ? 'selected' : '' }}>Semester 2</option>
                    <option value="3" {{ request('semester') == '3' ? 'selected' : '' }}>Semester 3</option>
                    <option value="4" {{ request('semester') == '4' ? 'selected' : '' }}>Semester 4</option>
                    <option value="5" {{ request('semester') == '5' ? 'selected' : '' }}>Semester 5</option>
                    <option value="6" {{ request('semester') == '6' ? 'selected' : '' }}>Semester 6</option>
                </select>

                <label class="mr-sm-2" for="filterMakul">Mata Kuliah:</label>
                <select class="form-control mb-2 mb-sm-0 mr-sm-3" name="kode_mk" id="filterMakul"
                    style="min-width: 200px;">
                    <option value="">Semua Mata Kuliah</option>
                    @foreach($daftar_matkul as $mk)
                        <option value="{{ $mk->kode_mk }}" {{ request('kode_mk') == $mk->kode_mk ? 'selected' : '' }}>
                            {{ $mk->nama_mk }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>

                {{-- Tombol Reset hanya muncul jika ada filter aktif --}}
                @if(request()->has('semester') || request()->has('kode_mk'))
                    <a href="{{ route('rekap-nilai.index') }}" class="btn btn-default ml-2">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="card card-outline card-success">

            {{-- ── Header Card: Judul & Tombol Export ────────────────────── --}}
            <div class="card-header">
                <h3 class="card-title mt-1">Data Rekap Nilai Mahasiswa</h3>
                <div class="card-tools">
                    {{-- request()->all() dikirim agar filter yang sedang aktif
                    ikut terbawa ke hasil export PDF/Excel --}}
                    <a href="{{ route('rekap-nilai.pdf', request()->all()) }}" target="_blank"
                        class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Cetak PDF
                    </a>
                    <a href="{{ route('rekap-nilai.excel', request()->all()) }}" target="_blank"
                        class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>

            {{-- ── Tabel Rekap Nilai ──────────────────────────────────────── --}}
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-nowrap m-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" style="width: 5%">No</th>
                                <th>Nama Mahasiswa</th>
                                <th class="text-center" style="width: 10%">Semester</th>
                                <th>Mata Kuliah</th>
                                <th class="text-center" style="width: 8%">Tugas</th>
                                <th class="text-center" style="width: 8%">UTS</th>
                                <th class="text-center" style="width: 8%">UAS</th>
                                <th class="text-center" style="width: 10%">Nilai Akhir</th>
                                <th class="text-center" style="width: 8%">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($nilais as $index => $nilai)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    {{-- Fallback ke nama user login jika relasi mahasiswa kosong
                                    (mis. saat mahasiswa mengakses rekapnya sendiri) --}}
                                    <td>{{ $nilai->mahasiswa->nama ?? Auth::user()->name }}</td>
                                    <td class="text-center">{{ $nilai->mataKuliah->semester ?? '-' }}</td>
                                    <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                                    <td class="text-center">{{ $nilai->nilai_tugas }}</td>
                                    <td class="text-center">{{ $nilai->nilai_uts }}</td>
                                    <td class="text-center">{{ $nilai->nilai_uas }}</td>
                                    <td class="text-center">{{ number_format($nilai->nilai_akhir, 2) }}</td>
                                    <td class="text-center">
                                        <span class="badge badge-success">{{ $nilai->grade }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Belum ada data rekap nilai.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- ── Footer Pagination ──────────────────────────────────────── --}}
            <div class="card-footer clearfix">
                <ul class="pagination pagination-sm m-0 float-right">
                    <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul>
            </div>
        </div>

    </div>
</div>
@stop