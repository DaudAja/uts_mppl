<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Warung Makan - Pesan Makanan Online</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 font-sans p-6"
      x-data="{ pesanModal: {{ $errors->any() ? 'true' : 'false' }}, makananId: '{{ old('makanan_id') }}', showStatus: true }">

    <div class="max-w-4xl mx-auto flex justify-between items-center mb-10">
        <h1 class="text-2xl font-bold text-gray-800">Warung Makan</h1>
        <a href="{{ route('login') }}" class="text-sm text-blue-600 underline hover:text-blue-800">Login Admin</a>
    </div>

    <div class="max-w-md mx-auto space-y-4 mb-12">

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
            <h2 class="text-sm font-bold mb-3">Cek Status Pesanan</h2>
            <form action="{{ route('cek.status') }}" method="GET" class="flex gap-2">
                <input type="number" name="nomor" placeholder="Nomor HP Anda"
                    class="border rounded px-3 py-2 flex-1 text-sm focus:ring-2 focus:ring-blue-500 outline-none"
                    value="{{ request('nomor') }}" required>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 transition">
                    Cek
                </button>
            </form>

            @if (isset($pesanan))
                <div x-show="showStatus" class="relative mt-4 p-3 border-l-4 {{ $pesanan->status == 'diproses' ? 'border-green-500 bg-green-50' : ($pesanan->status == 'stok_habis' ? 'border-red-500 bg-red-50' : 'border-yellow-500 bg-yellow-50') }}">
                    <button @click="showStatus = false" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <p class="text-sm italic text-gray-600">Hasil untuk: {{ $pesanan->nomor_telepon }}</p>
                    <p class="text-sm">Halo <strong>{{ $pesanan->nama_pemesan }}</strong>,</p>
                    <p class="text-sm">Status Pesanan:
                        <span class="uppercase font-black {{ $pesanan->status == 'diproses' ? 'text-green-600' : ($pesanan->status == 'stok_habis' ? 'text-red-600' : 'text-yellow-600') }}">
                            {{ $pesanan->status }}
                        </span>
                    </p>
                </div>
            @endif
        </div>

        @if (session('success'))
            <div class="p-4 bg-green-100 text-green-700 border border-green-200 rounded-xl text-sm font-bold text-center shadow-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div class="p-4 bg-red-100 text-red-700 border border-red-200 rounded-xl text-sm font-bold text-center shadow-sm">
                ❌ {{ session('error') ?? 'Gagal memesan, periksa kembali data Anda.' }}
            </div>
        @endif

        <button @click="pesanModal = true; showStatus = false"
            class="w-full bg-green-600 text-white py-4 rounded-xl font-bold text-lg hover:bg-green-700 transition shadow-lg active:scale-95">
            Pesan Makanan Sekarang 🍽️
        </button>

    </div>

    <div class="max-w-4xl mx-auto">
        <h2 class="font-bold mb-4 text-gray-700 border-b pb-2">Daftar Menu Tersedia</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($makanans as $item)
                <div class="bg-white border rounded-lg p-4 shadow-sm hover:shadow-md transition">
                    <img src="{{ Str::startsWith($item->foto, 'http') ? $item->foto : asset('storage/' . $item->foto) }}"
                        class="w-full h-40 object-cover rounded mb-4 bg-gray-200">
                    <h3 class="font-bold text-lg">{{ $item->nama }}</h3>
                    <p class="text-gray-600 text-sm mb-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                    <p class="text-xs {{ $item->stok > 0 ? 'text-emerald-500' : 'text-red-500' }} italic font-medium">
                        Sisa Stok: {{ $item->stok }}
                    </p>
                </div>
            @empty
                <p class="text-center col-span-3 text-gray-500">Belum ada menu makanan tersedia.</p>
            @endforelse
        </div>
    </div>

    <div x-show="pesanModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50" x-cloak>
        <div class="bg-white w-full max-w-sm p-6 rounded-lg shadow-xl" @click.away="pesanModal = false">
            <h3 class="font-bold text-lg mb-4 text-center text-gray-800 border-b pb-2">Form Pemesanan</h3>

            <form action="{{ route('pesanan.simpan') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold mb-1">Pilih Menu</label>
                    <select name="makanan_id" x-model="makananId"
                        class="w-full border rounded px-3 py-2 text-sm bg-gray-50 @error('makanan_id') border-red-500 @enderror"
                        required>
                        <option value="">-- Silakan Pilih Menu --</option>
                        @foreach ($makanans as $item)
                            <option value="{{ $item->id }}">
                                {{ $item->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('makanan_id')
                        <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mt-2 min-h-[20px]">
                    @foreach ($makanans as $item)
                        <template x-if="makananId == {{ $item->id }}">
                            <div class="flex justify-between w-full bg-gray-50 p-2 rounded border border-dashed border-gray-300">
                                <span class="text-[11px] font-bold text-gray-500">
                                    Harga: <span class="text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                </span>
                                <span class="text-[11px] font-bold {{ $item->stok > 0 ? 'text-gray-500' : 'text-red-500' }}">
                                    Stok: {{ $item->stok }}
                                </span>
                            </div>
                        </template>
                    @endforeach
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Nama Pemesan</label>
                    <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}"
                        placeholder="Nama Lengkap Anda"
                        class="w-full border rounded px-3 py-2 text-sm @error('nama_pemesan') border-red-500 @enderror"
                        required>
                    @error('nama_pemesan')
                        <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold mb-1">Nomor WhatsApp</label>
                    <input type="number" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                        placeholder="Contoh: 08123456789"
                        class="w-full border rounded px-3 py-2 text-sm @error('nomor_telepon') border-red-500 @enderror"
                        required>
                    @error('nomor_telepon')
                        <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="pesanModal = false"
                        class="flex-1 py-2 text-sm border rounded hover:bg-gray-100 transition">Batal</button>
                    <button type="submit"
                        class="flex-1 py-2 text-sm bg-green-600 text-white rounded font-bold hover:bg-green-700 transition">
                        Kirim Pesanan
                    </button>
                </div>
            </form>
        </div>
    </div>

</body>

</html>

