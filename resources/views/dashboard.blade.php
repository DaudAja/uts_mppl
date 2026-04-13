<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Manajemen Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 rounded-xl border border-emerald-200 shadow-sm font-bold text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-6 py-4 text-center">Waktu</th>
                                    <th class="px-6 py-4">Pelanggan</th>
                                    <th class="px-6 py-4">Menu</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($semua_pesanan as $pesanan)
                                <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-center dark:text-slate-300">
                                        <span class="font-bold">{{ $pesanan->created_at->format('H:i') }}</span>
                                        <span class="block text-[10px] text-slate-400">{{ $pesanan->created_at->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $pesanan->nama_pemesan }}</div>
                                        <div class="text-xs text-slate-500">{{ $pesanan->nomor_telepon }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium dark:text-slate-300 italic">
                                        {{ $pesanan->makanan->nama }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('admin.pesanan.update-status', $pesanan->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <select name="status" onchange="this.form.submit()"
                                                class="text-[11px] font-bold uppercase py-1 px-3 rounded-full border-none focus:ring-2 focus:ring-slate-300 cursor-pointer
                                                {{ $pesanan->status == 'menunggu' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $pesanan->status == 'diproses' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $pesanan->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}
                                                {{ $pesanan->status == 'stok_habis' ? 'bg-slate-200 text-slate-700' : '' }}">
                                                <option value="menunggu" {{ $pesanan->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                                <option value="diproses" {{ $pesanan->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                                <option value="stok_habis" {{ $pesanan->status == 'stok_habis' ? 'selected' : '' }}>Stok Habis</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            @if($pesanan->status == 'menunggu')
                                            <form action="{{ route('admin.pesanan.proses', $pesanan->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button title="Terima Pesanan" class="p-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif

                                            <form action="{{ route('admin.pesanan.destroy', $pesanan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button title="Hapus Data" class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center text-slate-500 italic">
                                        Belum ada pesanan masuk.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
