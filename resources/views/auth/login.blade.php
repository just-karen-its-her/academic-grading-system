@extends('adminlte::master')

@section('title', 'Login Akademik')

@section('classes_body', 'hold-transition login-page')

@section('body')
<div class="login-box">

    {{-- Form Login dibungkus dalam satu Card utuh yang solid --}}
    <div class="card card-outline card-primary shadow-lg border-0">

        <div class="card-header text-center pt-4 pb-2 border-bottom-0">
            <a href="#" class="h2 text-dark text-decoration-none" style="font-weight: normal;">
                <b>Sistem</b> Penilaian
            </a>
            <p class="text-muted mt-2 mb-0" style="font-size: 1.05rem; font-weight: 500;">
                Rekap Nilai Mahasiswa
            </p>
        </div>

        <div class="card-body login-card-body pt-3">

            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="icon fas fa-info-circle"></i> Akun Demo</h5>
                <ul class="mb-0 pl-3">
                    <li>
                        <b>Mahasiswa</b><br>
                        NIM: <code>1062587</code><br>
                        Pass: <code>password123</code>
                    </li>
                    <li class="mt-2">
                        <b>Dosen</b><br>
                        NIP/NIDN: <code>22345678</code><br>
                        Pass: <code>password123</code>
                    </li>
                </ul>
            </div>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                {{-- Nomor Induk --}}
                <div class="input-group mb-3">
                    <input type="text" name="nomor_induk"
                        class="form-control @error('nomor_induk') is-invalid @enderror"
                        placeholder="Nomor Induk (NIM/NIP)" value="{{ old('nomor_induk') }}" required autofocus>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-id-card"></span>
                        </div>
                    </div>

                    @error('nomor_induk')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="input-group mb-4">
                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                        placeholder="{{ __('adminlte::adminlte.password') }}" required>

                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>

                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                {{-- Remember & Submit --}}
                <div class="row align-items-center mb-2">
                    <div class="col-7">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" style="font-weight: normal; cursor: pointer;">
                                {{ __('adminlte::adminlte.remember_me') }}
                            </label>
                        </div>
                    </div>

                    <div class="col-5">
                        <button type="submit" class="btn btn-primary btn-block font-weight-bold">
                            {{ __('adminlte::adminlte.sign_in') }} <i class="fas fa-sign-in-alt ml-1"></i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>
@stop