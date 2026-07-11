<!DOCTYPE html>
<html>

<head>
    <title>Transkrip Akademik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .header-text {
            text-align: center;
            margin-bottom: 20px;
        }

        .profil-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .profil-table td {
            padding: 5px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px;
        }

        .data-table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header-text">
        <h2>Transkrip Akademik Mahasiswa</h2>
    </div>

    <table class="profil-table">
        <tr>
            <td width="15%"><b>NIM</b></td>
            <td width="35%">: {{ $profil->nim ?? Auth::user()->nomor_induk }}</td>
            <td width="15%"><b>Program Studi</b></td>
            <td width="35%">: {{ $profil->jurusan ?? 'Tidak Diketahui' }}</td>
        </tr>
        <tr>
            <td><b>Nama Lengkap</b></td>
            <td colspan="3">: {{ $profil->nama ?? Auth::user()->name }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Semester</th>
                <th width="15%">Kode MK</th>
                <th>Mata Kuliah</th>
                <th width="10%">SKS</th>
                <th width="15%">Nilai Angka</th>
                <th width="10%">Grade</th>
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
                    <td class="text-center">{{ $nilai->grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data nilai.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <th colspan="4" class="text-right">Total SKS Kumulatif</th>
                <th class="text-center">{{ $total_sks ?? 0 }}</th>
                <th class="text-right">IPK</th>
                <th class="text-center">{{ number_format($ipk, 2) }}</th>
            </tr>
        </tfoot>
    </table>

</body>

</html>