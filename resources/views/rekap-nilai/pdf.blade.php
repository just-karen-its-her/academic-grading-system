<!DOCTYPE html>
<html>

<head>
    <title>Rekap Nilai Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>

    <h2>Rekap Nilai Mahasiswa</h2>
    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama Mahasiswa</th>
                <th style="width: 10%">Semester</th>
                <th>Mata Kuliah</th>
                <th style="width: 8%">Tugas</th>
                <th style="width: 8%">UTS</th>
                <th style="width: 8%">UAS</th>
                <th style="width: 10%">Nilai Akhir</th>
                <th style="width: 8%">Grade</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nilais as $index => $nilai)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $nilai->mahasiswa->nama ?? Auth::user()->name }}</td>
                    <td class="text-center">{{ $nilai->mataKuliah->semester ?? '-' }}</td>
                    <td>{{ $nilai->mataKuliah->nama_mk ?? '-' }}</td>
                    <td class="text-center">{{ $nilai->nilai_tugas }}</td>
                    <td class="text-center">{{ $nilai->nilai_uts }}</td>
                    <td class="text-center">{{ $nilai->nilai_uas }}</td>
                    <td class="text-center">{{ number_format($nilai->nilai_akhir, 2) }}</td>
                    <td class="text-center">{{ $nilai->grade }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Belum ada data rekap nilai.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>