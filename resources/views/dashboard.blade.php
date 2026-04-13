<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-200 leading-tight">
            {{ __('Daftar Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-900 overflow-hidden shadow-sm sm:rounded-2xl border border-slate-200 dark:border-slate-800">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800 dark:text-slate-400">
                                <tr>
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Pelanggan</th>
                                    <th class="px-6 py-4">Menu Dipesan</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                @forelse($semua_pesanan as $pesanan)
                                <tr class="bg-white dark:bg-slate-900 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap dark:text-slate-300">
                                        {{ $pesanan->created_at->format('H:i') }}
                                        <span class="block text-[10px] text-slate-400">{{ $pesanan->created_at->format('d M') }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900 dark:text-white">{{ $pesanan->nama_pemesan }}</div>
                                        <div class="text-xs text-slate-500">{{ $pesanan->nomor_telepon }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium dark:text-slate-300">
                                        {{ $pesanan->makanan->nama_makanan }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($pesanan->status == 'menunggu')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 rounded-full text-[11px] font-bold uppercase tracking-wider">Menunggu</span>
                                        @elseif($pesanan->status == 'diproses')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-full text-[11px] font-bold uppercase tracking-wider">Diproses</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 rounded-full text-[11px] font-bold uppercase tracking-wider">Stok Habis</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex justify-center gap-2">
                                        @if($pesanan->status == 'menunggu')
                                        <form action="{{ route('admin.pesanan.proses', $pesanan->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                                Terima
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.pesanan.habis', $pesanan->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">
                                                Stok Habis
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                                        Belum ada pesanan masuk hari ini.
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
