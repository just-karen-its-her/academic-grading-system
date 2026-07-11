@extends('adminlte::page')

@section('title', 'Edit Nilai')

@section('content_header')
<h1>Edit Nilai</h1>
@stop

@section('content')
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit mr-1"></i> Form Edit Nilai
        </h3>
    </div>

    <form action="{{ route('nilai.update', $nilai->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            {{-- Error Validasi --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fas fa-ban"></i> Terdapat Kesalahan!</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Mahasiswa --}}
            <div class="form-group">
                <label for="mahasiswa_id">Mahasiswa <span class="text-danger">*</span></label>
                <select name="mahasiswa_nim" id="mahasiswa_id"
                    class="form-control select2 @error('mahasiswa_nim') is-invalid @enderror">
                    <option value="">-- Pilih Mahasiswa --</option>
                    @foreach ($mahasiswas as $mhs)
                        <option value="{{ $mhs->nim }}" {{ old('mahasiswa_nim', $nilai->mahasiswa_nim) == $mhs->nim ? 'selected' : '' }}>
                            {{ $mhs->nim }} - {{ $mhs->nama }}
                        </option>
                    @endforeach
                </select>
                @error('mahasiswa_nim')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Mata Kuliah --}}
            <div class="form-group">
                <label for="mata_kuliah_id">Mata Kuliah <span class="text-danger">*</span></label>
                <select name="mata_kuliah_kode" id="mata_kuliah_id"
                    class="form-control select2 @error('mata_kuliah_kode') is-invalid @enderror">
                    <option value="">-- Pilih Mata Kuliah --</option>
                    @foreach ($mataKuliahs as $mk)
                        <option value="{{ $mk->kode_mk }}" data-dosen="{{ $mk->dosen ?? '' }}" {{ old('mata_kuliah_kode', $nilai->mata_kuliah_kode) == $mk->kode_mk ? 'selected' : '' }}>
                            {{ $mk->kode_mk }} - {{ $mk->nama_mk }}
                        </option>
                    @endforeach
                </select>
                @error('mata_kuliah_kode')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            {{-- Komponen Nilai --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nilai_tugas">
                            Nilai Tugas <span class="badge badge-secondary">30%</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="nilai_tugas" id="nilai_tugas"
                            class="form-control komponen-nilai @error('nilai_tugas') is-invalid @enderror"
                            value="{{ old('nilai_tugas', $nilai->nilai_tugas) }}" min="0" max="100" step="0.01"
                            placeholder="0 – 100">
                        @error('nilai_tugas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nilai_uts">
                            Nilai UTS <span class="badge badge-secondary">30%</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="nilai_uts" id="nilai_uts"
                            class="form-control komponen-nilai @error('nilai_uts') is-invalid @enderror"
                            value="{{ old('nilai_uts', $nilai->nilai_uts) }}" min="0" max="100" step="0.01"
                            placeholder="0 – 100">
                        @error('nilai_uts')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="nilai_uas">
                            Nilai UAS <span class="badge badge-secondary">40%</span>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="number" name="nilai_uas" id="nilai_uas"
                            class="form-control komponen-nilai @error('nilai_uas') is-invalid @enderror"
                            value="{{ old('nilai_uas', $nilai->nilai_uas) }}" min="0" max="100" step="0.01"
                            placeholder="0 – 100">
                        @error('nilai_uas')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Preview Nilai Akhir (live) --}}
            <div class="alert alert-warning mt-2" id="preview-nilai">
                <h5 class="mb-1">
                    Nilai Akhir: <strong id="preview-akhir">{{ number_format($nilai->nilai_akhir, 2) }}</strong>
                    <span id="preview-grade" class="badge ml-1">{{ $nilai->grade }}</span>
                </h5>
                <small class="text-muted">
                    Nilai Akhir = (Tugas × 30%) + (UTS × 30%) + (UAS × 40%)
                </small>
            </div>

        </div>{{-- end card-body --}}

        <div class="card-footer">
            <button type="submit" class="btn btn-warning">
                <i class="fas fa-save mr-1"></i> Perbarui
            </button>
            <a href="{{ route('nilai.index') }}" class="btn btn-default ml-1">
                <i class="fas fa-times mr-1"></i> Batal
            </a>
        </div>
    </form>
</div>
@stop

@section('plugins.Select2', true)

@section('js')
<script>
    $(function () {
        // Select2
        $('.select2').select2({ theme: 'bootstrap4' });

        // Auto-fill Dosen
        $('#mata_kuliah_id').on('change', function () {
            const selected = $(this).find(':selected');
            $('#dosen').val(selected.data('dosen') || '');
        });

        // Live Kalkulasi Nilai Akhir & Grade
        const badgeMap = {
            A: 'success',
            B: 'primary',
            C: 'warning',
            D: 'secondary',
            E: 'danger',
        };

        function hitungGrade(na) {
            if (na >= 85) return 'A';
            if (na >= 70) return 'B';
            if (na >= 55) return 'C';
            if (na >= 40) return 'D';
            return 'E';
        }

        function updatePreview() {
            const tugas = parseFloat($('#nilai_tugas').val()) || 0;
            const uts = parseFloat($('#nilai_uts').val()) || 0;
            const uas = parseFloat($('#nilai_uas').val()) || 0;

            const na = (tugas * 0.30) + (uts * 0.30) + (uas * 0.40);
            const grade = hitungGrade(na);
            const color = badgeMap[grade];

            $('#preview-akhir').text(na.toFixed(2));
            // Hapus custom CSS, gunakan class default AdminLTE/Bootstrap
            $('#preview-grade')
                .text(grade)
                .attr('class', `badge badge-${color} ml-1`);
        }

        // Jalankan saat nilai berubah
        $(document).on('input', '.komponen-nilai', updatePreview);

        // Jalankan sekali saat halaman dimuat
        updatePreview();
    });
</script>
@stop