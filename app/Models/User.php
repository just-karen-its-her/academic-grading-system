<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User
 *
 * Model autentikasi utama (dosen & mahasiswa berbagi tabel yang sama,
 * dibedakan lewat kolom 'role'). Login menggunakan 'nomor_induk',
 * bukan email.
 */
#[Fillable(['name', 'nomor_induk', 'role', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // email_verified_at dihapus (tidak dipakai, login pakai nomor_induk)
            'password' => 'hashed',
        ];
    }
}