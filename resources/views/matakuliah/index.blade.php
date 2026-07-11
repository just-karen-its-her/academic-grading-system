@extends('adminlte::page')
@section('title', 'Data Mata Kuliah')
@section('content_header')
<h1>Data Mata Kuliah</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="tabel-mk" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th>Kode MK</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Semester</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Melakukan looping data dari database -->
                    @foreach($mata_kuliahs as $index => $mk)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $mk->kode_mk }}</td>
                            <td>{{ $mk->nama_mk }}</td>
                            <td>{{ $mk->sks }}</td>
                            <td>{{ $mk->semester }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    $(document).ready(function () {
        $('#tabel-mk').DataTable();
    }); 
</script>
@stop       