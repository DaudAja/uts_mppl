<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemesanan Makanan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans p-6" x-data="{ pesanModal: false }">

    <div class="max-w-4xl mx-auto flex justify-between items-center mb-10">
        <h1 class="text-2xl font-bold text-gray-800">Warung Makan</h1>
        <a href="{{ route('login') }}" class="text-sm text-blue-600 underline">Login Admin</a>
    </div>

    <div class="max-w-md mx-auto space-y-4 mb-12">

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-sm font-bold mb-3">Cek Status Pesanan</h2>
            <form action="{{ route('cek.status') }}" method="GET" class="flex gap-2">
                <input type="number" name="nomor" placeholder="Nomor HP Anda"
                    class="border rounded px-3 py-2 flex-1 text-sm" required>
                <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold">Cek</button>
                @if (session('success'))
                    <div
                        class="max-w-md mx-auto mt-4 p-4 bg-green-100 text-green-700 border border-green-200 rounded-lg text-sm font-bold text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="max-w-md mx-auto mt-4 p-4 bg-red-100 text-red-700 border border-red-200 rounded-lg text-sm font-bold text-center">
                        {{ session('error') }}
                    </div>
                @endif
            </form>

            @if (isset($pesanan))
                <div
                    class="mt-4 p-3 border-l-4 {{ $pesanan->status == 'diproses' ? 'border-green-500 bg-green-50' : 'border-yellow-500 bg-yellow-50' }}">
                    <p class="text-sm">Halo <strong>{{ $pesanan->nama_pemesan }}</strong>,</p>
                    <p class="text-sm">Status: <span class="uppercase font-black">{{ $pesanan->status }}</span></p>
                </div>
            @endif
        </div>

        <button @click="pesanModal = true"
            class="w-full bg-green-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-green-700 transition shadow-lg">
            Pesan Makanan Sekarang 🍽️
        </button>

    </div>

    <div class="max-w-4xl mx-auto">
        <h2 class="font-bold mb-4 text-gray-700">Daftar Menu Tersedia:</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($makanans as $item)
                <div class="bg-white border rounded-lg p-4 shadow-sm">
                    <img src="{{ Str::startsWith($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}"
                        class="w-full h-40 object-cover rounded mb-4">
                    <h3 class="font-bold text-lg">{{ $item->nama }}</h3>
                    <p class="text-gray-600 text-sm mb-1">Rp {{ number_format($item->harga) }}</p>
                    <p class="text-xs text-red-500 italic">Sisa Stok: {{ $item->stok }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div x-show="pesanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white w-full max-w-sm p-6 rounded-lg shadow-xl" @click.away="pesanModal = false">
            <h3 class="font-bold text-lg mb-4 text-center">Form Pemesanan</h3>

            <form action="{{ route('pesanan.simpan') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold mb-1">Pilih Menu</label>
                    <select name="makanan_id" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" required>
                        <option value="">-- Silakan Pilih Menu --</option>
                        @foreach ($makanans as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama }} (Rp {{ number_format($item->harga) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" placeholder="Nama Anda"
                        class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Nomor WhatsApp</label>
                    <input type="number" name="nomor_telepon" placeholder="08xxxx"
                        class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="pesanModal = false"
                        class="flex-1 py-2 text-sm border rounded hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2 text-sm bg-green-600 text-white rounded font-bold hover:bg-green-700">Kirim
                        Pesanan</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
