<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 *
 * Menangani proses autentikasi user: menampilkan form login,
 * memproses login (via nomor_induk & password), dan logout.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        // Pastikan file view login.blade.php sudah ada
        return view('auth.login');
    }

    /**
     * Memproses percobaan login menggunakan nomor_induk & password.
     * Semua role diarahkan ke halaman yang sama (/dashboard) tanpa
     * pengecekan role di sini.
     */
    public function prosesLogin(Request $request)
    {
        $credentials = $request->validate([
            'nomor_induk' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Hapus pengecekan role, langsung arahkan semua user ke /dashboard
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'nomor_induk' => 'Nomor Induk atau Password salah.',
        ]);
    }

    /**
     * Melakukan logout: hapus sesi & regenerasi token CSRF,
     * lalu arahkan kembali ke halaman login.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}