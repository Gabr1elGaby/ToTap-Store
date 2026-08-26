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

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-400 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm font-semibold">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <!-- VIP Reseller Live Balance Card -->
            <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6 transition-colors duration-200">
                <div class="space-y-1">
                    <div class="flex items-center gap-2.5">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-500/20 shadow-sm shrink-0">
                            <i class="fas fa-wallet text-xl"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-base font-black text-gray-900 dark:text-white">Saldo Akun VIP Reseller (Live API)</h3>
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span> Real-Time Auto-Update
                                </span>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 max-w-xl">
                                Saldo tersinkronisasi langsung dari provider VIP Reseller. Nominal produk yang harga modalnya di atas saldo ini akan otomatis berstatus <strong>Stok Habis</strong>.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-gray-100 dark:border-gray-700">
                    <div class="text-left md:text-right">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Saldo Terkini di VIP Reseller</div>
                        <div class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">
                            Rp{{ number_format($vipBalance ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <form action="{{ route('admin.games.sync-balance') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-4 py-3 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer whitespace-nowrap" title="Sinkronkan Saldo Terbaru dari VIP Reseller">
                            <i class="fas fa-sync-alt"></i> Refresh Saldo
                        </button>
                    </form>
                </div>
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
                                            <form action="{{ route('admin.games.destroy', $game) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus game {{ $game->name }} beserta seluruh produknya?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-red-600 dark:text-red-400 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 transition cursor-pointer" title="Hapus Game">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if(method_exists($games, 'links'))
                    <div class="mt-6">
                        {{ $games->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>