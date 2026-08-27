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
                
                <div class="flex flex-wrap items-center gap-3 w-full md:w-auto justify-between md:justify-end border-t md:border-t-0 pt-3 md:pt-0 border-gray-100 dark:border-gray-700">
                    <div class="text-left md:text-right mr-2">
                        <div class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Saldo Terkini di VIP</div>
                        <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight">
                            Rp{{ number_format($vipBalance ?? 0, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <form action="{{ route('admin.games.sync-balance') }}" method="POST">
                            @csrf
                            <button type="submit" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold text-xs transition flex items-center gap-1.5 cursor-pointer whitespace-nowrap shadow-sm" title="Sinkronkan Saldo Terbaru dari VIP Reseller">
                                <i class="fas fa-wallet text-emerald-500"></i> Refresh Saldo
                            </button>
                        </form>
                        <form action="{{ route('admin.games.sync-product-status') }}" method="POST" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Mengecek...';">
                            @csrf
                            <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-lg shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer whitespace-nowrap" title="Cek status ketersediaan/stok produk dari VIP Reseller tanpa mengubah harga editan Anda">
                                <i class="fas fa-sync-alt"></i> Cek Status Stok VIP
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Category Filter Tabs & Add Button Bar -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('admin.games.index', ['category' => 'all']) }}" 
                       class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ ($categoryFilter === 'all' || !$categoryFilter) ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                        <span>🌟</span> Semua Layanan
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ ($categoryFilter === 'all' || !$categoryFilter) ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $totalAll ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.games.index', ['category' => 'game']) }}" 
                       class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ $categoryFilter === 'game' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                        <span>🎮</span> Top Up Game
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $categoryFilter === 'game' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $totalGame ?? 0 }}</span>
                    </a>
                    <a href="{{ route('admin.games.index', ['category' => 'app-premium']) }}" 
                       class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 {{ $categoryFilter === 'app-premium' ? 'bg-amber-500 text-white shadow-md shadow-amber-500/30' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-200 dark:border-gray-700' }}">
                        <span>👑</span> Aplikasi Premium
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-black {{ $categoryFilter === 'app-premium' ? 'bg-white/20 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }}">{{ $totalApp ?? 0 }}</span>
                    </a>
                </div>

                <a href="{{ route('admin.games.create', ['category' => ($categoryFilter === 'app-premium' ? 'Aplikasi Premium' : '')]) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-xs {{ $categoryFilter === 'app-premium' ? 'bg-amber-500 hover:bg-amber-600 text-white shadow-md shadow-amber-500/30' : 'bg-indigo-600 hover:bg-indigo-700 text-white shadow-md shadow-indigo-600/30' }} transition cursor-pointer">
                    <i class="fas fa-plus"></i> Tambah {{ $categoryFilter === 'app-premium' ? 'Aplikasi Premium' : ($categoryFilter === 'game' ? 'Game Baru' : 'Layanan Baru') }}
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