<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * RoleMiddleware
 *
 * Membatasi akses route berdasarkan role user yang sedang login.
 * Role yang diizinkan ditentukan lewat parameter middleware,
 * contoh: ->middleware(RoleMiddleware::class . ':dosen')
 */
class RoleMiddleware
{
    /**
     * Menangani request masuk: izinkan lanjut jika user login
     * dan role-nya sesuai, jika tidak tolak dengan HTTP 403.
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        // Cek apakah user sudah login dan role-nya sesuai dengan yang diminta rute
        if (Auth::check() && Auth::user()->role == $role) {
            return $next($request); // Izinkan masuk
        }

        // Jika tidak sesuai, tampilkan halaman error 403 (Akses Ditolak)
        abort(403, 'Akses Ditolak: Halaman ini khusus untuk ' . strtoupper($role));
    }
}