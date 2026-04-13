<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Makanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    // Menampilkan daftar semua pesanan di halaman Dashboard Admin
    public function dashboardAdmin()
    {
        $semua_pesanan = Pesanan::with('makanan')->latest()->get();
        return view('dashboard', compact('semua_pesanan'));
    }

    // Fungsi untuk menyimpan pesanan baru dari halaman depan
    public function simpanPesanan(Request $request)
    {
        $request->validate([
            'makanan_id' => 'required',
            'nama_pemesan' => 'required|min:3',
            'nomor_telepon' => 'required|numeric|digits_between:10,13',
        ], [
            'nomor_telepon.required' => 'Nomor WhatsApp wajib diisi.',
            'nomor_telepon.digits_between' => 'Nomor WhatsApp minimal 10 digit dan maksimal 13 digit.',
        ]);

        $makanan = Makanan::find($request->makanan_id);

        if ($makanan->stok <= 0) {
            return back()->with('error', 'Maaf, stok ' . $makanan->nama . ' sudah habis!');
        }

        Pesanan::create([
            'makanan_id' => $request->makanan_id,
            'nama_pemesan' => $request->nama_pemesan,
            'nomor_telepon' => $request->nomor_telepon,
            'status' => 'menunggu',
        ]);

        $makanan->decrement('stok');

        return redirect('/')->with('success', 'Pesanan berhasil dikirim! Silakan cek status nanti.');
    }

    // Fungsi untuk cek status pesanan menggunakan nomor HP
    public function cekStatus(Request $request)
    {
        $pesanan = Pesanan::where('nomor_telepon', $request->nomor)
                          ->with('makanan')
                          ->latest()
                          ->first();

        $makanans = Makanan::all();

        return view('welcome', compact('pesanan', 'makanans'));
    }

    /* |--------------------------------------------------------------------------
    | FUNGSI TAMBAHAN UNTUK ADMIN (Dropdown & Hapus)
    |--------------------------------------------------------------------------
    */

    // Mengubah status pesanan melalui dropdown di dashboard
    public function updateStatus(Request $request, $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $request->validate([
            'status' => 'required|in:menunggu,ditolak,stok_habis'
        ]);

        $pesanan->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan ' . $pesanan->nama_pemesan . ' berhasil diperbarui!');
    }

    // Menghapus data pesanan secara permanen
    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        $pesanan->delete();

        return back()->with('success', 'Data pesanan berhasil dihapus.');
    }
}
