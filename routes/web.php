<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MakananController; // Pastikan Anda buat controller ini
use App\Http\Controllers\PesananController; // Pastikan Anda buat controller ini
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Route Akses Publik (Tanpa Login)
|--------------------------------------------------------------------------
*/

// Menampilkan halaman utama dengan daftar menu
Route::get('/', [MakananController::class, 'index'])->name('home');

// Mengirim pesanan dari modal di halaman utama
Route::post('/pesan', [PesananController::class, 'simpanPesanan'])->name('pesanan.simpan');

// Mencari status pesanan berdasarkan nomor telepon
Route::get('/cek-status', [PesananController::class, 'cekStatus'])->name('cek.status');


/*
|--------------------------------------------------------------------------
| Route Akses Admin (Harus Login)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard Admin: Melihat tabel semua pesanan yang masuk
    Route::get('/dashboard', [PesananController::class, 'dashboardAdmin'])->name('dashboard');

    // Aksi Admin untuk mengubah status pesanan (Proses/Stok Habis)
    Route::patch('/admin/pesanan/{id}/proses', [PesananController::class, 'prosesPesanan'])->name('admin.pesanan.proses');
    Route::patch('/admin/pesanan/{id}/habis', [PesananController::class, 'stokHabis'])->name('admin.pesanan.habis');

    // Route CRUD Makanan (Tambah, Edit, Hapus Menu)
    Route::resource('makanan', MakananController::class);

    // Route Profile Bawaan Laravel Breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
