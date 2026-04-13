<?php

namespace App\Http\Controllers;

use App\Models\Makanan; // Pastikan Model Makanan sudah dibuat
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MakananController extends Controller
{
    // 1. Menampilkan menu di halaman depan (untuk User)
    public function index()
    {
        // Ambil semua data makanan
        $makanans = Makanan::all();

        // Kirim ke view dengan nama 'makanans'
        return view('welcome', compact('makanans'));
    }

    // 2. Menampilkan daftar menu di dashboard (untuk Admin)
    public function daftarMenuAdmin()
    {
        $makanans = Makanan::all();
        return view('admin.makanan.index', compact('makanans'));
    }

    // 3. Menyimpan menu baru (Proses CRUD)
    public function store(Request $request)
    {
        $request->validate([
            'nama_makanan' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|numeric',
            'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('menu', 'public');
        }

        Makanan::create([
            'nama_makanan' => $request->nama_makanan,
            'harga' => $request->harga,
            'stok' => $request->stok,
            'foto' => $path,
        ]);

        return back()->with('success', 'Menu baru berhasil ditambahkan!');
    }

    // 4. Menghapus menu
    public function destroy(Makanan $makanan)
    {
        if ($makanan->foto) {
            Storage::disk('public')->delete($makanan->foto);
        }
        $makanan->delete();
        return back()->with('success', 'Menu berhasil dihapus!');
    }
}
