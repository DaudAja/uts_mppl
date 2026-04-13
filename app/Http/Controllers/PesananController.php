<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Makanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Fungsi yang menyebabkan error tadi
    public function dashboardAdmin()
    {
        // Ambil semua pesanan dan urutkan dari yang terbaru
        $semua_pesanan = Pesanan::with('makanan')->latest()->get();

        return view('dashboard', compact('semua_pesanan'));
    }

    // Fungsi untuk menyimpan pesanan dari User
    public function simpanPesanan(Request $request)
{
    // 1. Validasi input agar tidak kosong dan nomor HP sesuai
    $request->validate([
        'makanan_id' => 'required',
        'nama_pemesan' => 'required|min:3',
        'nomor_telepon' => 'required|numeric|digits_between:10,13', // Ini kuncinya!
    ], [
        'nomor_telepon.required' => 'Nomor WhatsApp wajib diisi.',
        'nomor_telepon.digits_between' => 'Nomor WhatsApp minimal 10 digit dan maksimal 13 digit.',
    ]);

    // 2. Cek apakah stok makanan masih ada
    $makanan = \App\Models\Makanan::find($request->makanan_id);
    if ($makanan->stok <= 0) {
        return back()->with('error', 'Maaf, stok ' . $makanan->nama . ' sudah habis!');
    }

    // 3. Simpan data ke tabel pesanans
    \App\Models\Pesanan::create([
        'makanan_id' => $request->makanan_id,
        'nama_pemesan' => $request->nama_pemesan,
        'nomor_telepon' => $request->nomor_telepon,
        'status' => 'menunggu', // Status awal otomatis 'menunggu'
    ]);

    // 4. (Opsional) Kurangi stok makanan setelah dipesan
    $makanan->decrement('stok');

    // 5. Kembalikan ke halaman depan dengan pesan sukses
    return redirect('/')->with('success', 'Pesanan berhasil dikirim! Silakan cek status nanti.');
}

    // Fungsi untuk cek status via nomor HP
    public function cekStatus(Request $request)
    {
        $pesanan = Pesanan::where('nomor_telepon', $request->nomor)
                          ->with('makanan')
                          ->latest()
                          ->first();

        // Ambil data makanan agar halaman welcome tidak error variabel $makanans
        $makanans = Makanan::all();

        return view('welcome', compact('pesanan', 'makanans'));
    }
}
