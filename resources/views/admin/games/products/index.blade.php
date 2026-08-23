<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-700 transition shadow-sm">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Kelola Produk Nominal: {{ $game->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Nominal Aktif ({{ $products->count() }} Item)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Produk yang tampil hanya yang berstatus valid dan tersedia dari provider.</p>
                </div>
                <a href="{{ route('admin.games.products.sync', $game) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition flex items-center gap-2">
                    <i class="fas fa-sync-alt"></i> Tarik Data Otomatis (VIP Reseller)
                </a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm font-semibold text-sm">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/50 text-xs uppercase font-bold text-gray-500 dark:text-gray-400 tracking-wider border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3.5 px-4">Kode Produk</th>
                                    <th class="py-3.5 px-4">Nama Produk Nominal</th>
                                    <th class="py-3.5 px-4">Harga Modal</th>
                                    <th class="py-3.5 px-4">Harga Jual</th>
                                    <th class="py-3.5 px-4">Margin Untung</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($products as $prod)
                                <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                    <td class="py-3.5 px-4 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">{{ $prod->product_code }}</td>
                                    <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white">{{ $prod->name }}</td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">Rp{{ number_format($prod->price_modal, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-green-600 dark:text-green-400">Rp{{ number_format($prod->price_sell, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-indigo-600 dark:text-indigo-400">Rp{{ number_format($prod->price_sell - $prod->price_modal, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                            Available
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <a href="{{ route('admin.games.products.edit', ['game' => $game->id, 'product' => $prod->id]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @if($products->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-box-open text-4xl mb-2 text-gray-400"></i>
                                        <p class="font-semibold">Belum ada produk aktif untuk game ini. Silakan klik tombol Tarik Data Otomatis.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>