<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.games.products.index', $game) }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-700 transition shadow-sm">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Harga: {{ $product->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen" x-data="{
        priceModal: {{ (float)$product->price_modal }},
        priceSell: {{ (int)$product->price_sell }},
        isPromo: {{ $product->is_promo ? 'true' : 'false' }},
        priceNormal: {{ (int)($product->price_normal ?? 0) }},
        get profit() {
            return this.priceSell - this.priceModal;
        }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm font-bold text-sm">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-400 p-5 rounded-2xl shadow-sm">
                    <strong class="font-bold flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle"></i> Gagal Menyimpan:
                    </strong>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Info Modal & Margin Card -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Modal Provider</span>
                    <div class="text-xl font-black text-gray-900 dark:text-white mt-1 font-mono">
                        Rp{{ number_format($product->price_modal, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Harga Jual Website</span>
                    <div class="text-xl font-black text-emerald-600 dark:text-emerald-400 mt-1 font-mono" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(priceSell || 0)">
                        Rp{{ number_format($product->price_sell, 0, ',', '.') }}
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Estimasi Profit / Untung</span>
                    <div class="text-xl font-black mt-1 font-mono" :class="profit >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-500'" x-text="'Rp' + new Intl.NumberFormat('id-ID').format(profit || 0)">
                        Rp{{ number_format($product->price_sell - $product->price_modal, 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Form Edit Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-3xl border border-gray-200 dark:border-gray-700 p-8">
                <form action="{{ route('admin.games.products.update', ['game' => $game->id, 'product' => $product->id]) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nama Produk Nominal</label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Harga Jual (Di Website Anda) <span class="text-red-500">*</span></label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-1.5">Harga yang harus dibayar pembeli. Jika diskon aktif, ini adalah harga setelah diskon.</p>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                            <input type="number" step="1" name="price_sell" x-model.number="priceSell" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono font-bold text-lg text-emerald-600 dark:text-emerald-400" required>
                        </div>
                    </div>

                    <!-- Promo Section -->
                    <div class="p-6 bg-amber-500/10 dark:bg-amber-950/40 border border-amber-400 dark:border-amber-700 rounded-2xl space-y-4">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" name="is_promo" id="is_promo" value="1" x-model="isPromo" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300 cursor-pointer">
                            <label for="is_promo" class="text-sm font-black text-gray-900 dark:text-white cursor-pointer flex items-center gap-2">
                                <span>🔥</span> Aktifkan Tanda Promo & Diskon Coret
                            </label>
                        </div>
                        
                        <div x-show="isPromo" class="space-y-1.5 pt-2">
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Harga Normal (Sebelum Diskon)</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Angka ini akan dicoret (Contoh: <del class="text-red-500">Rp25.000</del>). Buat lebih besar dari harga jual.</p>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 font-bold text-gray-500 dark:text-gray-400 text-sm">Rp</span>
                                <input type="number" step="1" name="price_normal" x-model.number="priceNormal" placeholder="Contoh: 25000" class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-mono text-sm">
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status Ketersediaan</label>
                        <select name="status" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                            <option value="available" {{ $product->status == 'available' ? 'selected' : '' }}>Tersedia (Available) - Tampil di Website</option>
                            <option value="empty" {{ $product->status == 'empty' ? 'selected' : '' }}>Kosong / Gangguan (Sembunyikan)</option>
                        </select>
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.games.products.index', $game) }}" class="px-5 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-600/30 transition cursor-pointer flex items-center gap-2 text-sm">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>