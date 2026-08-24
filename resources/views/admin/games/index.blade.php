<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Game & Top Up') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm font-semibold">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <!-- VIP Reseller Balance Setting Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="p-2 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                            <i class="fas fa-shield-alt text-lg"></i>
                        </span>
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Batas Saldo Modal VIP Reseller (Proteksi Stok)</h3>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xl">
                        Nominal game yang harga modalnya lebih besar dari saldo ini akan <strong class="text-red-500">otomatis dinonaktifkan (Stok Habis)</strong> agar pesanan tidak gagal.
                    </p>
                </div>
                
                <form action="{{ route('admin.games.update-balance') }}" method="POST" class="flex items-center gap-2 w-full md:w-auto">
                    @csrf
                    <div class="relative">
                        <span class="absolute left-3 top-2.5 text-xs font-bold text-gray-400">Rp</span>
                        <input type="number" name="balance" value="{{ $vipBalance ?? \App\Models\Setting::get('vip_balance_threshold', 0) }}" min="0" step="1000" required
                            class="pl-9 pr-3 py-2 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-bold w-40 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md transition whitespace-nowrap">
                        Simpan Saldo
                    </button>
                </form>
            </div>

            <!-- Header & Action -->
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Game Aktif</h3>
                <a href="{{ route('admin.games.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition">
                    + Tambah Game Baru
                </a>
            </div>

            <!-- Games Table -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/50 text-xs uppercase font-bold text-gray-500 dark:text-gray-400 tracking-wider border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3.5 px-4">Game</th>
                                    <th class="py-3.5 px-4">Kategori</th>
                                    <th class="py-3.5 px-4">Jumlah Nominal</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($games as $game)
                                <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                    <td class="py-3.5 px-4">
                                        <div class="flex items-center gap-3">
                                            @if($game->thumbnail)
                                                <img src="{{ $game->thumbnail }}" class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-gray-700">
                                            @endif
                                            <div>
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $game->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $game->slug }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300 font-medium">
                                        {{ $game->category ?? 'Game' }}
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                            {{ $game->products_count }} Item
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $game->is_active ? 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20' }}">
                                            {{ $game->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.games.products.index', $game) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition">
                                                <i class="fas fa-list"></i> Atur Produk
                                            </a>
                                            <a href="{{ route('admin.games.edit', $game) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $games->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>