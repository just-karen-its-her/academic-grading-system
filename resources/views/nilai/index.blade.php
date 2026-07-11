@extends('adminlte::page')

@section('title', 'Nilai Mahasiswa')

@section('content_header')
<div class="row mb-2">
    <div class="col-sm-6">
        <h1 class="m-0 text-dark">Nilai Data Mahasiswa</h1>
    </div>
    <div class="col-sm-6">
        <a href="{{ route('nilai.create') }}" class="btn btn-primary float-sm-right">
            <i class="fas fa-plus mr-1"></i> Input Nilai Mahasiswa
        </a>
    </div>
</div>
@stop

@section('content')

{{-- Flash Messages --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
        {{ session('error') }}
    </div>
@endif

{{-- Card Tabel Nilai --}}
<div class="card card-primary card-outline">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-table mr-1"></i> Daftar Nilai Mahasiswa
        </h3>
    </div>

    <div class="card-body table-responsive p-0">
        <table id="tabel-nilai" class="table table-bordered table-striped table-hover text-nowrap m-0">
            <thead class="bg-primary text-center">
                <tr>
                    <th>NO Urut</th>
                    <th>Mahasiswa</th>
                    <th>Mata Kuliah</th>
                    <th>Tugas</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Nilai Akhir</th>
                    <th>Grade</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nilais as $index => $nilai)
                    <tr>
                        <td class="text-center align-middle">{{ $nilais->firstItem() + $index }}</td>
                        <td class="align-middle">
                            <strong>{{ $nilai->mahasiswa->nama ?? '-' }}</strong><br>
                            <span class="text-muted">{{ $nilai->mahasiswa->nim ?? '' }}</span>
                        </td>
                        <td class="align-middle">
                            <strong>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</strong><br>
                            <span class="text-muted">{{ $nilai->mataKuliah->kode_mk ?? '' }}</span>
                        </td>
                        <td class="text-center align-middle">{{ number_format($nilai->nilai_tugas, 0) }}</td>
                        <td class="text-center align-middle">{{ number_format($nilai->nilai_uts, 0) }}</td>
                        <td class="text-center align-middle">{{ number_format($nilai->nilai_uas, 0) }}</td>
                        <td class="text-center align-middle">
                            <strong>{{ number_format($nilai->nilai_akhir, 2) }}</strong>
                        </td>
                        <td class="text-center align-middle">
                            @php
                                $badgeColor = match ($nilai->grade) {
                                    'A' => 'success',
                                    'B' => 'primary',
                                    'C' => 'warning',
                                    'D' => 'secondary',
                                    default => 'danger',
                                };
                            @endphp
                            {{-- Class badge standar bawaan AdminLTE --}}
                            <span class="badge badge-{{ $badgeColor }}">
                                {{ $nilai->grade }}
                            </span>
                        </td>
                        <td class="text-center align-middle">
                            <div class="btn-group">
                                <a href="{{ route('nilai.show', $nilai->id) }}" class="btn btn-sm btn-info" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('nilai.edit', $nilai->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('nilai.destroy', $nilai->id) }}" method="POST"
                                    class="form-hapus d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-5">
                            <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                            <h5>Belum ada data nilai</h5>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer Pagination --}}
    @if($nilais->hasPages())
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                {{ $nilais->links() }}
            </ul>
        </div>
    @endif
</div>
@stop

{{-- Plugins bawaan jeroennoten/Laravel-AdminLTE --}}
@section('plugins.Datatables', true)
@section('plugins.Sweetalert2', true)

@section('js')
{{-- CDN cadangan agar pop-up SweetAlert2 pasti muncul --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function () {
        // Inisialisasi DataTable
        $('#tabel-nilai').DataTable({
            "paging": false,
            "ordering": true,
            "info": false,
            "responsive": true,
            "searching": true,
            "language": {
                "search": "Cari:",
                "zeroRecords": "Data tidak ditemukan."
            }
        });

        // Konfirmasi SweetAlert sebelum hapus
        $(document).on('submit', '.form-hapus', function (e) {
            e.preventDefault(); // Tahan form agar tidak langsung terhapus
            const form = this;  // Simpan data form ke dalam variabel

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data nilai yang dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
                cancelButtonText: '<i class="fas fa-times mr-1"></i> Batal',
                reverseButtons: true // Opsional: Bikin tombol 'Batal' ada di kiri
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika user klik 'Ya, Hapus!', barulah form dikirim ke Controller
                    form.submit();
                }
            });
        });
    });
</script>
@stop