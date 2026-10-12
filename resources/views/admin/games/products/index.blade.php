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

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200" x-data="{ 
        openCreateModal: false, 
        openStockModal: false,
        activeProduct: '',
        activeProductName: '',
        stockList: [],
        loadingStocks: false,
        priceModal: 0, 
        priceSell: 0, 
        isPromo: false, 
        priceNormal: 0,
        async loadStocks(prodId) {
            this.loadingStocks = true;
            try {
                const res = await fetch('/admin/games/{{ $game->id }}/products/' + prodId + '/stocks');
                const data = await res.json();
                this.stockList = data.stocks || [];
            } catch(e) {
                this.stockList = [];
            }
            this.loadingStocks = false;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Daftar Nominal ({{ $products->where('status', 'available')->count() }} Aktif / {{ $products->count() }} Total)</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Produk berstatus Kosong/Gangguan otomatis disembunyikan dari halaman pembelian pembeli.</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <button type="button" @click="openCreateModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-4 rounded-xl text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                        <i class="fas fa-plus-circle"></i> Tambah Nominal Manual
                    </button>
                    <form action="{{ route('admin.games.sync-single-status', $game) }}" method="POST" onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Mengecek...';">
                        @csrf
                        <button type="submit" class="bg-indigo-500/10 hover:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-500/30 font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer shadow-sm" title="Cek status ketersediaan/stok produk {{ $game->name }} di VIP Reseller tanpa mengubah harga editan Anda">
                            <i class="fas fa-check-double"></i> Cek Status VIP
                        </button>
                    </form>
                    <form action="{{ route('admin.games.products.cleanup-non-idr', $game) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus seluruh produk nominal selain mata uang Rupiah/IDR (seperti PHP, MYR, THB, INR, USD, dll)?');">
                        @csrf
                        <button type="submit" class="bg-rose-500/10 hover:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-500/30 font-bold py-2.5 px-4 rounded-xl text-xs transition flex items-center gap-1.5 cursor-pointer shadow-sm">
                            <i class="fas fa-broom"></i> Bersihkan Non-IDR (Asing)
                        </button>
                    </form>
                    <a href="{{ route('admin.games.products.sync', $game) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-5 rounded-xl text-xs shadow-md transition flex items-center gap-2">
                        <i class="fas fa-sync-alt"></i> Tarik Data Otomatis (VIP Reseller)
                    </a>
                </div>
            </div>
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-4 py-3 rounded-2xl flex items-center gap-2 shadow-sm font-semibold text-sm">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-400 p-4 rounded-2xl shadow-sm text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                    <th class="py-3.5 px-4">Stok VIP</th>
                                    <th class="py-3.5 px-4">Status</th>
                                    <th class="py-3.5 px-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($products as $prod)
                                <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                    <td class="py-3.5 px-4 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">{{ $prod->product_code }}</td>
                                    @php
                                        $displayName = $prod->name;
                                        // Hapus rincian bonus dalam kurung (misal: "5 Diamonds ( 5 + 0 Bonus )" -> "5 Diamonds")
                                        $displayName = preg_replace('/\s*\(\s*\d+\s*(?:\+\s*\d+)?\s*(?:Bonus|bonus)?\s*\)/i', '', $displayName);
                                        
                                        if (preg_match('/(?:Mobile Legends|Free Fire)\s*-\s*(\d+)\s*Diamonds?\s*\+\s*(\d+)\s*Bonus/i', $displayName, $m)) {
                                            $displayName = ((int)$m[1] + (int)$m[2]) . " Diamonds";
                                        } elseif (preg_match('/(?:Mobile Legends|Free Fire)\s*-\s*(\d+)\s*\+\s*(\d+)\s*Diamonds?/i', $displayName, $m)) {
                                            $displayName = ((int)$m[1] + (int)$m[2]) . " Diamonds";
                                        } elseif (preg_match('/(?:Mobile Legends|Free Fire)\s*-\s*([^#]+?)(?:\s*\(#\d+\))?$/i', $displayName, $m)) {
                                            $displayName = trim($m[1]);
                                        }
                                        
                                        $displayName = preg_replace('/\s*\(#\d+\)/', '', $displayName);
                                        $displayName = preg_replace('/\s+Tested$/i', '', $displayName);
                                        $displayName = preg_replace('/\s+2x Bonus$/i', '', $displayName);
                                        $displayName = trim($displayName);
                                    @endphp
                                    <td class="py-3.5 px-4 font-bold text-gray-900 dark:text-white">{{ $displayName }}</td>
                                    <td class="py-3.5 px-4 text-gray-600 dark:text-gray-300">Rp{{ number_format($prod->price_modal, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 font-bold text-green-600 dark:text-green-400">Rp{{ number_format($prod->price_sell, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4 text-xs font-semibold text-indigo-600 dark:text-indigo-400">Rp{{ number_format($prod->price_sell - $prod->price_modal, 0, ',', '.') }}</td>
                                    <td class="py-3.5 px-4">
                                        @if(isset($prod->stock_info['label']))
                                            @if(($prod->stock_info['status'] ?? '') === 'available')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                                    <i class="fas fa-cubes text-[10px] mr-1"></i> {{ $prod->stock_info['label'] }}
                                                </span>
                                            @elseif(($prod->stock_info['status'] ?? '') === 'empty')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20">
                                                    <i class="fas fa-exclamation-circle text-[10px] mr-1"></i> {{ $prod->stock_info['label'] }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-500/10 text-slate-600 dark:text-slate-400 border border-slate-500/20">
                                                    {{ $prod->stock_info['label'] }}
                                                </span>
                                            @endif
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4">
                                        @if($prod->status === 'available')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 text-rose-600 dark:text-rose-400 border border-rose-500/20" title="Kosong atau gangguan dari provider">
                                                Kosong / Gangguan
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            @if($prod->provider === 'manual')
                                                <button type="button" @click="activeProduct = '{{ $prod->id }}'; activeProductName = '{{ addslashes($prod->name) }}'; loadStocks('{{ $prod->id }}'); openStockModal = true;" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 transition cursor-pointer" title="Kelola Stok Akun / Token">
                                                    <i class="fas fa-boxes"></i> Stok ({{ $prod->stocks_count ?? 0 }})
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.games.products.edit', ['game' => $game->id, 'product' => $prod->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.games.products.destroy', ['game' => $game->id, 'product' => $prod->id]) }}" method="POST" onsubmit="return confirm('Hapus nominal ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-xs font-bold text-rose-600 dark:text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 border border-rose-500/30 transition cursor-pointer">
                                                    <i class="fas fa-trash-alt"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @if($products->isEmpty())
                                <tr>
                                    <td colspan="8" class="text-center py-12 text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-box-open text-4xl mb-2 text-gray-400"></i>
                                        <p class="font-semibold">Belum ada produk aktif untuk produk ini.</p>
                                        <p class="text-xs text-gray-400 mt-1">Silakan klik tombol <button type="button" @click="openCreateModal = true" class="text-emerald-600 font-bold underline hover:text-emerald-700">Tambah Nominal Manual</button> atau Tarik Data Otomatis jika dari VIP Reseller.</p>
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Nominal Manual -->
        <div x-show="openCreateModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.away="openCreateModal = false" 
                 class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-lg p-6 sm:p-8 space-y-6 max-h-[90vh] overflow-y-auto">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-plus-circle text-emerald-500"></i> Tambah Nominal Manual
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Produk/Layanan: {{ $game->name }}</p>
                    </div>
                    <button type="button" @click="openCreateModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('admin.games.products.store', $game) }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Nama Produk Nominal <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Claude Code API $100" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold text-sm" required>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Kode Produk (Opsional)</label>
                        <input type="text" name="product_code" placeholder="Contoh: CLAUDE-100 (kosongkan jika otomatis)" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-xs">
                        <p class="text-[11px] text-gray-400 mt-0.5">Bisa dikosongkan, sistem akan membuat kode otomatis.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Harga Modal (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 font-bold text-gray-400 text-xs">Rp</span>
                                <input type="number" step="1" name="price_modal" x-model.number="priceModal" placeholder="0" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono font-bold text-sm" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Harga Jual (Rp) <span class="text-red-500">*</span></label>
                            <div class="relative mt-1">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 font-bold text-emerald-500 text-xs">Rp</span>
                                <input type="number" step="1" name="price_sell" x-model.number="priceSell" placeholder="0" class="w-full pl-9 pr-3 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400" required>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 bg-slate-50 dark:bg-gray-900/60 rounded-xl flex items-center justify-between text-xs font-bold border border-gray-200 dark:border-gray-700">
                        <span class="text-gray-500 dark:text-gray-400">Estimasi Untung/Margin:</span>
                        <span :class="(priceSell - priceModal) >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-500'" x-text="'Rp' + new Intl.NumberFormat('id-ID').format((priceSell || 0) - (priceModal || 0))">Rp0</span>
                    </div>

                    <!-- Promo Checkbox -->
                    <div class="p-4 bg-amber-500/10 border border-amber-400/40 rounded-xl space-y-2">
                        <label class="flex items-center gap-2 cursor-pointer text-xs font-bold text-gray-800 dark:text-gray-200">
                            <input type="checkbox" name="is_promo" value="1" x-model="isPromo" class="rounded text-indigo-600 focus:ring-indigo-500">
                            <span>🔥 Pasang Diskon / Harga Coret</span>
                        </label>
                        <div x-show="isPromo" class="space-y-1 pt-1">
                            <label class="block text-[11px] font-bold text-gray-600 dark:text-gray-400">Harga Normal (Sebelum Diskon)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 font-bold text-gray-400 text-xs">Rp</span>
                                <input type="number" step="1" name="price_normal" x-model.number="priceNormal" placeholder="Contoh: 150000" class="w-full pl-9 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-xs">
                            </div>
                        </div>
                    </div>

                    <!-- Input Stok Akun / Token API -->
                    <div class="space-y-1.5 p-4 bg-slate-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200 dark:border-gray-700">
                        <label class="block text-xs font-black text-gray-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-boxes text-emerald-500"></i> Input Stok Token / Script API (Bisa Banyak Sekaligus)
                        </label>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 leading-relaxed">
                            Tempelkan 4 script API yang Anda punya sekaligus di sini (pisahkan dengan enter kosong). Sistem otomatis mendeteksi setiap script sebagai <strong>1 stok</strong>. Saat customer beli, mereka otomatis hanya menerima 1 stok dan sisa stok Anda berkurang 1.
                        </p>
                        <textarea name="stocks_input" rows="5" class="w-full mt-1 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 font-mono text-xs text-emerald-600 dark:text-emerald-400 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500" placeholder="set ANTHROPIC_BASE_URL=https://api.mwapi.dev&#10;set ANTHROPIC_AUTH_TOKEN=sk-xxx1&#10;set CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1&#10;&#10;set ANTHROPIC_BASE_URL=https://api.mwapi.dev&#10;set ANTHROPIC_AUTH_TOKEN=sk-xxx2&#10;set CLAUDE_CODE_DISABLE_NONESSENTIAL_TRAFFIC=1"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status Ketersediaan</label>
                        <select name="status" class="w-full mt-1.5 p-2.5 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold text-sm">
                            <option value="available" selected>Tersedia (Available) - Tampil di Website</option>
                            <option value="empty">Kosong / Habis (Sembunyikan)</option>
                        </select>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-2 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="openCreateModal = false" class="px-4 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-2.5 rounded-xl shadow-md transition flex items-center gap-1.5 text-xs cursor-pointer">
                            <i class="fas fa-save"></i> Simpan Nominal
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Kelola Stok Produk -->
        <div x-show="openStockModal" 
             x-cloak 
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            
            <div @click.away="openStockModal = false" 
                 class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 w-full max-w-2xl p-6 sm:p-8 space-y-6 max-h-[90vh] overflow-y-auto">
                
                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-boxes text-emerald-500"></i> Kelola Stok Akun / Token
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Produk: <span class="font-bold text-indigo-600 dark:text-indigo-400" x-text="activeProductName"></span></p>
                    </div>
                    <button type="button" @click="openStockModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg cursor-pointer">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <!-- Form Tambah Stok Baru -->
                <form :action="'/admin/games/{{ $game->id }}/products/' + activeProduct + '/stocks'" method="POST" class="p-4 bg-slate-50 dark:bg-gray-900/60 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-3">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-1">
                            + Tambah Stok Token / Script Baru
                        </label>
                        <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-1.5">
                            Tempelkan 1 atau beberapa script/token API di sini (pisahkan dengan enter kosong).
                        </p>
                        <textarea name="stocks_input" rows="4" class="w-full p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 font-mono text-xs text-emerald-600 dark:text-emerald-400 placeholder-slate-400 focus:ring-2 focus:ring-emerald-500" placeholder="Paste script/token baru di sini..." required></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-xl text-xs shadow-md transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-plus-circle"></i> Tambah ke Stok
                        </button>
                    </div>
                </form>

                <!-- Daftar Stok yang Tersedia & Terpakai -->
                <div class="space-y-3">
                    <h4 class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center justify-between">
                        <span>Daftar Stok (<span x-text="stockList.filter(s => s.status === 'available').length"></span> Tersedia / <span x-text="stockList.length"></span> Total)</span>
                    </h4>

                    <div x-show="loadingStocks" class="text-center py-6 text-gray-400 text-xs">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Memuat data stok...
                    </div>

                    <div x-show="!loadingStocks && stockList.length === 0" class="text-center py-6 text-gray-400 text-xs">
                        Belum ada stok untuk produk ini. Silakan tempelkan script di atas untuk menambahkan stok.
                    </div>

                    <div x-show="!loadingStocks && stockList.length > 0" class="space-y-2 max-h-60 overflow-y-auto pr-1">
                        <template x-for="item in stockList" :key="item.id">
                            <div class="p-3 rounded-xl border flex items-center justify-between gap-3 text-xs"
                                 :class="item.status === 'available' ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700' : 'bg-slate-100 dark:bg-gray-900/40 border-gray-100 dark:border-gray-800 opacity-60'">
                                <div class="min-w-0 flex-1 space-y-0.5">
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-[10px] px-2 py-0.5 rounded-full font-bold"
                                              :class="item.status === 'available' ? 'bg-emerald-500/10 text-emerald-600 border border-emerald-500/20' : 'bg-slate-500/10 text-slate-500 border border-slate-500/20'"
                                              x-text="item.status === 'available' ? 'Tersedia' : 'Terpakai (Order #' + (item.transaction_id || '-') + ')'">
                                        </span>
                                        <span class="text-[11px] text-gray-400 font-mono" x-text="item.created_at"></span>
                                    </div>
                                    <div class="font-mono text-[11px] text-gray-700 dark:text-gray-300 truncate" x-text="item.content_preview"></div>
                                </div>

                                <template x-if="item.status === 'available'">
                                    <form :action="'/admin/games/{{ $game->id }}/products/' + activeProduct + '/stocks/' + item.id" method="POST" onsubmit="return confirm('Hapus 1 stok ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-500 hover:text-rose-700 p-1.5 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-950/40 transition cursor-pointer" title="Hapus Stok Ini">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                    <button type="button" @click="openStockModal = false" class="px-5 py-2.5 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition cursor-pointer">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>