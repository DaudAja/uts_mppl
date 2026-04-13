<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Makanan extends Model
{
    use HasFactory;

    // Menentukan nama tabel (opsional jika nama tabel Anda adalah 'makanans')
    protected $table = 'makanans';

    /**
     * Kolom yang boleh diisi (Mass Assignment).
     * Sesuaikan dengan nama kolom di migrasi Anda.
     */
    protected $fillable = [
        'nama',
        'harga',
        'stok',
        'foto',
    ];

    /**
     * Relasi ke model Pesanan.
     * Satu makanan bisa memiliki banyak pesanan.
     */
    public function pesanans()
    {
        return $this->hasMany(Pesanan::class);
    }
}
