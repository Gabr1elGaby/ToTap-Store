<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <span class="text-2xl">🎁</span> {{ __('Pengaturan Diskon & Promo Otomatis') }}
            </h2>
            <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-bold text-xs rounded-full border border-indigo-200 dark:border-indigo-800">
                Super Admin Control
            </span>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        // Simulator Alpine State
        simAmount: 50000,
        simModal: 45000,
        simEmail: '',
        simCategory: 'all',
        simResult: null,
        simLoading: false,
        async testDiscount() {
            this.simLoading = true;
            try {
                const res = await fetch('{{ route('admin.promos.simulate') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ amount: this.simAmount, modal: this.simModal, email: this.simEmail, category: this.simCategory })
                });
                this.simResult = await res.json();
            } catch (e) {
                alert('Gagal menjalankan simulasi: ' + e.message);
            }
            this.simLoading = false;
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Success Alert -->
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 p-4 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Top Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <!-- Status Hari Ini -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider block">Hari Ini (WIB)</span>
                        <h4 class="text-xl font-black text-gray-900 dark:text-white mt-1 flex items-center gap-2">
                            <span>📅 {{ $todayCheck['day_name'] ?: 'Hari Ini' }}</span>
                            @if($todayCheck['active'])
                                <span class="px-2.5 py-0.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-400 text-xs font-black rounded-full animate-pulse border border-emerald-300 dark:border-emerald-800">
                                    PROMO AKTIF
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-full">
                                    Non-Promo
                                </span>
                            @endif
                        </h4>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xl font-bold">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                </div>

                <!-- Total Pesanan Diskon -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider block">Pesanan Pakai Promo</span>
                        <h4 class="text-2xl font-black text-gray-900 dark:text-white mt-1">
                            {{ number_format($totalDiscountOrders) }} <span class="text-xs text-gray-500 font-semibold font-normal">transaksi</span>
                        </h4>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xl font-bold">
                        <i class="fas fa-tags"></i>
                    </div>
                </div>

                <!-- Total Diskon Diberikan -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider block">Total Potongan Diskon</span>
                        <h4 class="text-2xl font-black text-emerald-600 dark:text-emerald-400 mt-1 font-mono">
                            Rp{{ number_format($totalDiscountGiven, 0, ',', '.') }}
                        </h4>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xl font-bold">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                </div>
            </div>

            <!-- Form Pengaturan Promo -->
            <form action="{{ route('admin.promos.update') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- CARD 1: Diskon Khusus Pengguna Pertama -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border-2 border-indigo-300 dark:border-indigo-900/80 shadow-lg space-y-5 flex flex-col justify-between"
                         x-data="{
                            firstActive: {{ $settings['first_user_active'] ? 'true' : 'false' }},
                            firstType: '{{ $settings['first_user_type'] }}'
                         }">
                        
                        <div class="space-y-5">
                            <!-- Header Card 1 -->
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg font-bold">
                                        🎁
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-900 dark:text-white">Diskon Pengguna Baru</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Otomatis untuk pembeli yang pertama kali checkout</p>
                                    </div>
                                </div>

                                <!-- Toggle Switch & Button -->
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="firstActive = !firstActive"
                                            class="px-3 py-1.5 rounded-xl font-black text-xs transition shadow-sm cursor-pointer"
                                            :class="firstActive ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        <span x-text="firstActive ? '✓ AKTIF' : '✕ NONAKTIF'"></span>
                                    </button>
                                    <input type="checkbox" name="promo_first_user_active" value="1" x-model="firstActive" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Judul Promo -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                        Label / Nama Promo
                                    </label>
                                    <input type="text" name="promo_first_user_title" value="{{ old('promo_first_user_title', $settings['first_user_title']) }}" 
                                           placeholder="Contoh: Diskon Spesial Pengguna Baru"
                                           class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 block">Teks ini akan muncul di rincian tagihan pembeli dan invoice.</span>
                                </div>

                                <!-- Tipe Potongan (Persentase vs Tetap) -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">
                                        Bentuk Potongan
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold"
                                               :class="firstType === 'percent' ? 'bg-indigo-50 dark:bg-indigo-950/80 border-indigo-500 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-400'">
                                            <input type="radio" name="promo_first_user_type" value="percent" x-model="firstType" class="text-indigo-600">
                                            <span>Persentase (%)</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold"
                                               :class="firstType === 'fixed' ? 'bg-indigo-50 dark:bg-indigo-950/80 border-indigo-500 text-indigo-700 dark:text-indigo-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-400'">
                                            <input type="radio" name="promo_first_user_type" value="fixed" x-model="firstType" class="text-indigo-600">
                                            <span>Nominal Tetap (Rp)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nilai Potongan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            <span x-text="firstType === 'percent' ? 'Besar Diskon (%)' : 'Besar Potongan (Rp)'"></span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="any" min="0" name="promo_first_user_value" value="{{ old('promo_first_user_value', $settings['first_user_value']) }}" 
                                                   class="w-full pl-3.5 pr-10 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                                            <span class="absolute right-3.5 top-2.5 text-xs font-black text-gray-400" x-text="firstType === 'percent' ? '%' : 'Rp'"></span>
                                        </div>
                                    </div>

                                    <div x-show="firstType === 'percent'">
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            Maksimal Potongan (Rp)
                                        </label>
                                        <input type="number" min="0" name="promo_first_user_max_discount" value="{{ old('promo_first_user_max_discount', $settings['first_user_max_discount']) }}" 
                                               placeholder="0 = Tanpa batas"
                                               class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Isi 0 jika tidak dibatasi</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Minimal Belanja -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            Minimal Transaksi (Rp)
                                        </label>
                                        <input type="number" min="0" name="promo_first_user_min_spend" value="{{ old('promo_first_user_min_spend', $settings['first_user_min_spend']) }}" 
                                               placeholder="0 = Tanpa minimal"
                                               class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                                        <span class="text-[10px] text-gray-400 mt-0.5 block">Minimal total belanja pembeli</span>
                                    </div>

                                    <!-- Target Keuntungan Minimal (Proteksi Anti-Rugi) -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1 flex items-center justify-between">
                                            <span>🛡️ Target Keuntungan Toko Setelah Diskon (%)</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="any" min="0" max="100" name="promo_first_user_min_profit" value="{{ old('promo_first_user_min_profit', $settings['first_user_min_profit'] ?? 1) }}" 
                                                   placeholder="Contoh: 1"
                                                   class="w-full pl-3.5 pr-10 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-mono">
                                            <span class="absolute right-3.5 top-2.5 text-xs font-black text-gray-400">%</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5 block">Khusus transaksi pengguna baru, harga promo otomatis diatur agar toko Anda tetap untung minimal X% di atas modal provider.</span>
                                    </div>
                                </div>

                                <!-- Kategori Produk yang Berlaku -->
                                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">
                                        🎯 Kategori yang Mendapat Diskon
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($availableCategories as $catCode => $catLabel)
                                            @php
                                                $isCatChecked = in_array($catCode, $settings['first_user_categories'], true);
                                            @endphp
                                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs font-bold transition hover:border-indigo-500 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" name="promo_first_user_categories[]" value="{{ $catCode }}" {{ $isCatChecked ? 'checked' : '' }} class="rounded text-indigo-600 focus:ring-indigo-500">
                                                <span>{{ $catLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 block">Centang "Semua Kategori" atau pilih kategori spesifik (Game, Aplikasi Premium, dll).</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700/60 text-xs font-medium text-gray-500 dark:text-gray-400">
                            💡 <em>Sistem otomatis mendeteksi apakah akun user pernah memiliki pesanan sukses sebelumnya.</em>
                        </div>
                    </div>

                    <!-- CARD 2: Diskon Hari Tertentu Otomatis (Day-of-Week Promo) -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border-2 border-emerald-300 dark:border-emerald-900/80 shadow-lg space-y-5 flex flex-col justify-between"
                         x-data="{
                            dayActive: {{ $settings['day_promo_active'] ? 'true' : 'false' }},
                            dayType: '{{ $settings['day_promo_type'] }}'
                         }">
                        
                        <div class="space-y-5">
                            <!-- Header Card 2 -->
                            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg font-bold">
                                        📅
                                    </div>
                                    <div>
                                        <h3 class="text-base font-black text-gray-900 dark:text-white">Diskon Hari Spesial</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Promo rutin setiap hari tertentu (misal: Jumat / Weekend)</p>
                                    </div>
                                </div>

                                <!-- Toggle Switch & Button -->
                                <div class="flex items-center gap-2">
                                    <button type="button" @click="dayActive = !dayActive"
                                            class="px-3 py-1.5 rounded-xl font-black text-xs transition shadow-sm cursor-pointer"
                                            :class="dayActive ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300'">
                                        <span x-text="dayActive ? '✓ AKTIF' : '✕ NONAKTIF'"></span>
                                    </button>
                                    <input type="checkbox" name="promo_day_active" value="1" x-model="dayActive" class="w-5 h-5 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <!-- Judul Promo -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                        Label / Nama Promo Hari
                                    </label>
                                    <input type="text" name="promo_day_title" value="{{ old('promo_day_title', $settings['promo_day_title']) }}" 
                                           placeholder="Contoh: Promo Jumat Berkah / Weekend Seru"
                                           class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500">
                                </div>

                                <!-- Pilihan Hari Aktif -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">
                                        Pilih Hari Promo Aktif (Otomatis Berulang)
                                    </label>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                        @foreach($dayNames as $dayNum => $dayName)
                                            @php
                                                $isSelected = in_array((int)$dayNum, $settings['day_promo_days'], true);
                                                $isToday = ((int)\Carbon\Carbon::now('Asia/Jakarta')->dayOfWeek === (int)$dayNum);
                                            @endphp
                                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs font-bold transition hover:border-emerald-500 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" name="promo_day_days[]" value="{{ $dayNum }}" {{ $isSelected ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                                                <span>{{ $dayName }}</span>
                                                @if($isToday)
                                                    <span class="text-[9px] px-1.5 py-0.5 bg-emerald-500 text-white rounded font-black ml-auto">Hari Ini</span>
                                                @endif
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Tipe Potongan (Persentase vs Tetap) -->
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">
                                        Bentuk Potongan
                                    </label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold"
                                               :class="dayType === 'percent' ? 'bg-emerald-50 dark:bg-emerald-950/80 border-emerald-500 text-emerald-700 dark:text-emerald-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-400'">
                                            <input type="radio" name="promo_day_type" value="percent" x-model="dayType" class="text-emerald-600">
                                            <span>Persentase (%)</span>
                                        </label>
                                        <label class="flex items-center gap-2 p-3 rounded-xl border cursor-pointer transition text-xs font-bold"
                                               :class="dayType === 'fixed' ? 'bg-emerald-50 dark:bg-emerald-950/80 border-emerald-500 text-emerald-700 dark:text-emerald-300 shadow-sm' : 'bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-400'">
                                            <input type="radio" name="promo_day_type" value="fixed" x-model="dayType" class="text-emerald-600">
                                            <span>Nominal Tetap (Rp)</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Nilai Potongan -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            <span x-text="dayType === 'percent' ? 'Besar Diskon (%)' : 'Besar Potongan (Rp)'"></span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="any" min="0" name="promo_day_value" value="{{ old('promo_day_value', $settings['day_promo_value']) }}" 
                                                   class="w-full pl-3.5 pr-10 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono">
                                            <span class="absolute right-3.5 top-2.5 text-xs font-black text-gray-400" x-text="dayType === 'percent' ? '%' : 'Rp'"></span>
                                        </div>
                                    </div>

                                    <div x-show="dayType === 'percent'">
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            Maksimal Potongan (Rp)
                                        </label>
                                        <input type="number" min="0" name="promo_day_max_discount" value="{{ old('promo_day_max_discount', $settings['day_promo_max_discount']) }}" 
                                               placeholder="0 = Tanpa batas"
                                               class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono">
                                        <span class="text-[10px] text-gray-400 block mt-0.5">Isi 0 jika tidak dibatasi</span>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Minimal Belanja -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1">
                                            Minimal Transaksi (Rp)
                                        </label>
                                        <input type="number" min="0" name="promo_day_min_spend" value="{{ old('promo_day_min_spend', $settings['day_promo_min_spend']) }}" 
                                               placeholder="0 = Tanpa minimal"
                                               class="w-full px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono">
                                        <span class="text-[10px] text-gray-400 mt-0.5 block">Minimal total belanja pembeli</span>
                                    </div>

                                    <!-- Target Keuntungan Minimal (Proteksi Anti-Rugi) -->
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-1 flex items-center justify-between">
                                            <span>🛡️ Min. Keuntungan Toko (%)</span>
                                        </label>
                                        <div class="relative">
                                            <input type="number" step="any" min="0" max="100" name="promo_day_min_profit" value="{{ old('promo_day_min_profit', $settings['day_promo_min_profit'] ?? 2) }}" 
                                                   placeholder="Contoh: 2"
                                                   class="w-full pl-3.5 pr-10 py-2.5 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-bold focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono">
                                            <span class="absolute right-3.5 top-2.5 text-xs font-black text-gray-400">%</span>
                                        </div>
                                        <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold mt-0.5 block">Diskon tidak akan memotong laba di bawah persentase modal ini.</span>
                                    </div>
                                </div>

                                <!-- Kategori Produk yang Berlaku -->
                                <div class="pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-200 mb-2">
                                        🎯 Kategori yang Mendapat Diskon
                                    </label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                        @foreach($availableCategories as $catCode => $catLabel)
                                            @php
                                                $isCatChecked = in_array($catCode, $settings['day_promo_categories'], true);
                                            @endphp
                                            <label class="flex items-center gap-2 p-2.5 rounded-xl border cursor-pointer text-xs font-bold transition hover:border-emerald-500 bg-gray-50 dark:bg-gray-900 border-gray-300 dark:border-gray-700">
                                                <input type="checkbox" name="promo_day_categories[]" value="{{ $catCode }}" {{ $isCatChecked ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                                                <span>{{ $catLabel }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400 mt-1 block">Centang "Semua Kategori" atau pilih kategori spesifik.</span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-700/60 text-xs font-medium text-gray-500 dark:text-gray-400">
                            💡 <em>Promo otomatis aktif pada pukul 00:00 WIB dan berakhir pada 23:59 WIB pada hari yang Anda pilih.</em>
                        </div>
                    </div>
                </div>

                <!-- Tombol Simpan -->
                <div class="flex justify-end pt-2">
                    <button type="submit" class="px-8 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 active:scale-95 text-white font-black text-sm shadow-xl shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer">
                        <i class="fas fa-save"></i> Simpan & Terapkan Pengaturan Diskon
                    </button>
                </div>
            </form>

            <!-- SECTION 3: Simulator / Live Tester Diskon -->
            <div class="bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 text-white rounded-3xl p-6 sm:p-8 border border-indigo-500/30 shadow-2xl space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-indigo-500/20 pb-4">
                    <div>
                        <span class="text-xs font-black text-indigo-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fas fa-vial"></i> Live Discount Simulator
                        </span>
                        <h3 class="text-lg font-black text-white mt-1">Uji Coba & Simulasi Perhitungan Diskon</h3>
                        <p class="text-xs text-slate-400">Cek apakah konfigurasi diskon berjalan dengan benar sesuai nominal belanja dan status user.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3.5 items-end">
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Harga Jual Normal (Rp)</label>
                        <input type="number" x-model="simAmount" class="w-full px-3.5 py-2 bg-slate-800 text-white border border-slate-700 rounded-xl text-xs font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Harga Modal (Rp)</label>
                        <input type="number" x-model="simModal" placeholder="0" class="w-full px-3.5 py-2 bg-slate-800 text-white border border-slate-700 rounded-xl text-xs font-mono font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="sm:col-span-3">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Kategori Produk</label>
                        <select x-model="simCategory" class="w-full px-3.5 py-2 bg-slate-800 text-white border border-slate-700 rounded-xl text-xs font-bold focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            @foreach($availableCategories as $cCode => $cLabel)
                                <option value="{{ $cCode }}">{{ $cLabel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-slate-300 mb-1">Email / WA (Opsional)</label>
                        <input type="text" x-model="simEmail" placeholder="Akun Baru" class="w-full px-3.5 py-2 bg-slate-800 text-white border border-slate-700 rounded-xl text-xs focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="sm:col-span-2">
                        <button type="button" @click="testDiscount()" :disabled="simLoading" class="w-full py-2 px-3 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-bold text-xs shadow-lg transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <span x-show="!simLoading"><i class="fas fa-play mr-1"></i> Uji Coba</span>
                            <span x-show="simLoading"><i class="fas fa-spinner fa-spin"></i></span>
                        </button>
                    </div>
                </div>

                <!-- Hasil Simulasi -->
                <template x-if="simResult">
                    <div class="p-5 rounded-2xl bg-slate-800/80 border border-slate-700 text-xs space-y-3 font-mono">
                        <div class="flex items-center justify-between border-b border-slate-700/80 pb-2">
                            <span class="text-slate-400">Status Diskon:</span>
                            <span class="font-black px-2.5 py-1 rounded-lg text-xs"
                                  :class="simResult.calculation.has_discount ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/40' : 'bg-rose-500/20 text-rose-400 border border-rose-500/40'"
                                  x-text="simResult.calculation.has_discount ? '✓ DISKON DITERAPKAN' : '✕ TIDAK ADA DISKON'">
                            </span>
                        </div>

                        <template x-if="simResult.calculation.has_discount">
                            <div class="space-y-2 pt-1">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Promo yang Dipilih:</span>
                                    <span class="font-bold text-indigo-300" x-text="simResult.calculation.promo_title"></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Harga Asli:</span>
                                    <span class="font-bold text-white" x-text="'Rp' + Number(simResult.calculation.original_amount).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between text-emerald-400 font-bold">
                                    <span>Hemat Potongan:</span>
                                    <span x-text="'- Rp' + Number(simResult.calculation.discount_amount).toLocaleString('id-ID')"></span>
                                </div>
                                <div class="flex justify-between text-base font-black text-amber-300 pt-2 border-t border-slate-700">
                                    <span>Total Bayar Akhir:</span>
                                    <span x-text="'Rp' + Number(simResult.calculation.final_amount).toLocaleString('id-ID')"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

        </div>
    </div>
</x-app-layout>
