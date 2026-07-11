<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

/**
 * AppServiceProvider
 *
 * Mendaftarkan Gate otorisasi berbasis role: 'akses-dosen' dan
 * 'akses-mahasiswa', digunakan untuk membatasi akses fitur
 * tertentu sesuai role user yang sedang login.
 */
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate khusus untuk Dosen
        Gate::define('akses-dosen', function (User $user) {
            return $user->role === 'dosen';
        });

        // Gate khusus untuk Mahasiswa
        Gate::define('akses-mahasiswa', function (User $user) {
            return $user->role === 'mahasiswa';
        });
    }
}