<style>
    .::-webkit-scrollbar {
        width: 4px;
    }
    .::-webkit-scrollbar-track {
        background: transparent;
    }
    .::-webkit-scrollbar-thumb {
        background: #4b5563;
        border-radius: 4px;
    }
</style>
<x-app-layout>
    <x-slot name="header">
        @php
            $catLowerHeader = strtolower($game->category ?? '');
            $isAppHeader = str_contains($catLowerHeader, 'app') || str_contains($catLowerHeader, 'aplikasi') || str_contains($catLowerHeader, 'streaming') || str_contains($catLowerHeader, 'entertainment');
            $backUrl = $isAppHeader ? url('/aplikasi-premium') : url('/topup');
            $titlePrefix = $isAppHeader ? 'Langganan' : 'Top Up';
        @endphp
        <div class="flex items-center gap-4">
            <a href="{{ $backUrl }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white rounded-xl border border-gray-300 dark:border-gray-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight border-l-2 border-indigo-600 dark:border-indigo-400 pl-4">
                {{ $titlePrefix }} {{ $game->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-300 p-4 rounded-xl mb-6 shadow-md flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <strong class="font-bold">Gagal: </strong> {{ session('error') }}
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-300 p-4 rounded-xl mb-6 shadow-md flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            @php
                $gameName = strtolower($game->name);
                $catLower = strtolower($game->category ?? '');
                $isApp = str_contains($catLower, 'app') || str_contains($catLower, 'aplikasi') || str_contains($catLower, 'streaming') || str_contains($catLower, 'entertainment');
                $isRequiresZone = $game->requires_zone_id || str_contains($gameName, 'magic chess') || str_contains($gameName, 'mobile legend');
                $field1Label = $game->target_field_1 ?: ($isApp ? 'Alamat Email Aktif' : 'User ID');
                $field2Label = $game->target_field_2 ?: ($isApp ? 'Request Profil / Server' : 'Zone ID');
            @endphp

            <!-- Panduan Top Up -->
            <div class="bg-blue-50/80 dark:bg-gray-800 border-l-4 border-blue-600 dark:border-blue-500 rounded-2xl p-5 mb-8 shadow-sm dark:shadow-md border border-blue-100 dark:border-gray-700" data-aos="fade-down">
                <h3 class="font-bold text-lg mb-3 text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Panduan & Cara Pemesanan {{ $game->name }}
                </h3>
                <ul class="space-y-2 text-sm md:text-base text-gray-700 dark:text-gray-300">
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-600 dark:text-blue-400">1.</span>
                        <div>
                            <span>Masukkan data <strong>{{ $field1Label }}</strong> @if($isRequiresZone) dan <strong>{{ $field2Label }}</strong> @endif yang valid.</span>
                            
                            @if(!empty($game->guide_text))
                                <div class="text-xs text-indigo-700 dark:text-indigo-300 font-semibold mt-1 bg-indigo-50 dark:bg-indigo-950/60 p-2.5 rounded-lg border border-indigo-100 dark:border-indigo-800/40">
                                    <i class="fas fa-info-circle mr-1"></i> {{ $game->guide_text }}
                                </div>
                            @elseif($isApp)
                                <div class="text-xs text-indigo-700 dark:text-indigo-300 font-semibold mt-1 bg-indigo-50 dark:bg-indigo-950/60 p-2.5 rounded-lg border border-indigo-100 dark:border-indigo-800/40">
                                    <i class="fas fa-envelope mr-1"></i> Masukkan alamat email aktif Anda. Akun atau link invite/undangan akan dikirim melalui riwayat pesanan (invoice) atau email Anda.
                                </div>
                            @elseif(str_contains($gameName, 'mobile legend'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: <strong>12345678</strong> untuk User ID dan <strong>1234</strong> untuk Zone ID. (Klik avatar profil di pojok kiri atas).</div>
                            @elseif(str_contains($gameName, 'magic chess'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: <strong>12345678</strong> untuk User ID dan <strong>1234</strong> untuk Zone ID. (Buka game Magic Chess: Go Go &gt; klik Avatar Profil di pojok kiri atas untuk melihat User ID dan Zone ID).</div>
                            @elseif(str_contains($gameName, 'valorant'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: <strong>Jett#1234</strong> atau <strong>Username#TAG</strong> (Lengkap dengan tanda pagar #).</div>
                            @elseif(str_contains($gameName, 'free fire'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: 1234567890 (Player ID di profil game).</div>
                            @elseif(str_contains($gameName, 'roblox'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: Username Akun Roblox Anda.</div>
                            @elseif(str_contains($gameName, 'genshin'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: 800123456 (Server Asia).</div>
                            @elseif(str_contains($gameName, 'pubg'))
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Contoh: 5123456789 (User ID di profil game).</div>
                            @else
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">Pastikan data yang Anda masukkan valid agar pesanan tidak gagal.</div>
                            @endif
                        </div>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-600 dark:text-blue-400">2.</span>
                        <span>Pilih nominal item atau voucher yang Anda inginkan dari daftar yang tersedia.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-600 dark:text-blue-400">3.</span>
                        <span>Pilih salah satu metode pembayaran yang paling memudahkan Anda.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-600 dark:text-blue-400">4.</span>
                        <span>Klik tombol <strong>Beli Sekarang</strong> dan selesaikan pembayaran. @if($isApp) Undangan / akun akan dikirimkan otomatis ke Email & Invoice Anda! @elseif($game->category == 'Voucher' || str_contains($gameName, 'voucher') || str_contains($gameName, 'wallet')) Kode voucher akan otomatis tampil di invoice dan dikirimkan ke WhatsApp Anda! @else Pesanan akan diproses otomatis! @endif</span>
                    </li>
                </ul>
            </div>
        @php
            $productPromoMap = [];
            foreach ($game->products as $p) {
                $calc = \App\Helpers\PromoHelper::calculateDiscount(auth()->user(), (float)$p->price_sell, $game, (float)$p->price_modal);
                $productPromoMap[$p->id] = [
                    'original_price' => (float)$p->price_sell,
                    'original_formatted' => 'Rp' . number_format($p->price_sell, 0, ',', '.'),
                    'modal_price' => (float)$p->price_modal,
                    'has_discount' => $calc['has_discount'],
                    'default_promo_type' => $calc['promo_type'],
                    'default_final_amount' => $calc['final_amount'],
                    'default_final_formatted' => 'Rp' . number_format($calc['final_amount'], 0, ',', '.'),
                    'default_savings' => $calc['savings_text'],
                    'eligible_promos' => $calc['eligible_promos'] ?? [],
                ];
            }
        @endphp

        <div class="container mx-auto px-4 py-8">
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Kiri: Info Game -->
                <div class="w-full lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-lg rounded-2xl overflow-hidden sticky top-24">
                        <div class="relative w-full h-64 lg:h-48 bg-gradient-to-tr from-slate-900 via-indigo-950 to-slate-900 flex items-center justify-center overflow-hidden">
                            @if($game->cover_image)
                                <img src="{{ $game->cover_image }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            @elseif($game->thumbnail)
                                <img src="{{ $game->thumbnail }}" alt="{{ $game->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="text-4xl text-indigo-500"><i class="fas fa-gamepad"></i></div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-3 left-4 right-4">
                                <span class="px-2.5 py-1 bg-indigo-600 text-white font-bold text-xs rounded-lg shadow-md uppercase tracking-wider">{{ $game->category ?? 'Game' }}</span>
                            </div>
                        </div>
                        
                        <div class="p-5">
                            <h1 class="text-xl font-black text-gray-900 dark:text-white leading-tight">{{ $game->name }}</h1>
                            <p class="text-xs text-indigo-600 dark:text-indigo-400 font-bold mt-0.5">{{ $game->developer ?? 'Official Publisher' }}</p>
                            
                            <hr class="my-4 border-gray-100 dark:border-gray-700">
                            
                            <div class="space-y-2.5 text-xs">
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-bolt text-amber-500 w-4 text-center"></i>
                                    <span>Proses Otomatis & Cepat</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-shield-alt text-emerald-500 w-4 text-center"></i>
                                    <span>Layanan 100% Legal & Aman</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300">
                                    <i class="fas fa-headset text-indigo-500 w-4 text-center"></i>
                                    <span>Bantuan CS 24/7 Siaga</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Form Transaksi -->
                <div class="w-full lg:w-3/4" x-data="{
                    selectedProduct: sessionStorage.getItem('totap_product_{{ $game->slug }}') || null,
                    selectedPayment: sessionStorage.getItem('totap_payment_{{ $game->slug }}') || 'qris',
                    selectedPromo: sessionStorage.getItem('totap_promo_{{ $game->slug }}') || 'auto',
                    playerId: sessionStorage.getItem('totap_player_id_{{ $game->slug }}') || '',
                    zoneId: sessionStorage.getItem('totap_zone_id_{{ $game->slug }}') || '',
                    stockMap: {{ json_encode($stockMap ?? []) }},
                    productPromoMap: {{ json_encode($productPromoMap) }},
                    getAvailablePromos() {
                        if (!this.selectedProduct || !this.productPromoMap[this.selectedProduct]) return [];
                        return this.productPromoMap[this.selectedProduct].eligible_promos || [];
                    },
                    getCurrentPrice() {
                        if (!this.selectedProduct || !this.productPromoMap[this.selectedProduct]) return 0;
                        const item = this.productPromoMap[this.selectedProduct];
                        if (this.selectedPromo === 'none' || !item.eligible_promos || item.eligible_promos.length === 0) {
                            return item.original_price;
                        }
                        const chosen = item.eligible_promos.find(ep => ep.type === this.selectedPromo);
                        return chosen ? chosen.final_amount : item.default_final_amount;
                    },
                    fetchStock() {
                        fetch('{{ route('topup.stock-status', $game->slug) }}', { headers: { 'Accept': 'application/json' } })
                            .then(res => res.json())
                            .then(data => {
                                if (data && data.stock_map) {
                                    this.stockMap = data.stock_map;
                                    if (this.selectedProduct && this.stockMap[this.selectedProduct]) {
                                        this.selectedProduct = null;
                                    }
                                }
                            })
                            .catch(() => {});
                    },
                    init() {
                        this.$watch('selectedProduct', v => {
                            sessionStorage.setItem('totap_product_{{ $game->slug }}', v || '');
                            if (v && this.productPromoMap[v]) {
                                const item = this.productPromoMap[v];
                                if (item.eligible_promos && item.eligible_promos.length > 0) {
                                    const hasPromo = item.eligible_promos.some(ep => ep.type === this.selectedPromo);
                                    if (!hasPromo && this.selectedPromo !== 'none') {
                                        this.selectedPromo = item.default_promo_type;
                                    }
                                } else {
                                    this.selectedPromo = 'none';
                                }
                            }
                        });
                        this.$watch('selectedPayment', v => sessionStorage.setItem('totap_payment_{{ $game->slug }}', v || ''));
                        this.$watch('selectedPromo', v => sessionStorage.setItem('totap_promo_{{ $game->slug }}', v || 'auto'));
                        this.$watch('playerId', v => sessionStorage.setItem('totap_player_id_{{ $game->slug }}', v || ''));
                        this.$watch('zoneId', v => sessionStorage.setItem('totap_zone_id_{{ $game->slug }}', v || ''));
                        
                        // Auto-polling real-time setiap 3 detik & saat tab dibuka
                        setInterval(() => this.fetchStock(), 3000);
                        window.addEventListener('focus', () => this.fetchStock());

                        // Jika setelah login ada tanda auto-submit, jalankan submit otomatis
                        if (sessionStorage.getItem('totap_auto_submit_{{ $game->slug }}') === '1') {
                            sessionStorage.removeItem('totap_auto_submit_{{ $game->slug }}');
                            @auth
                                this.$nextTick(() => {
                                    if (this.selectedProduct && this.playerId) {
                                        const formEl = document.getElementById('topup-form');
                                        if (formEl) formEl.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
                                    }
                                });
                            @endauth
                        }
                    }
                }">
                    <form action="{{ route('topup.process', $game->slug) }}" method="POST" id="topup-form">
                        @csrf
                        <input type="hidden" name="product_id" x-model="selectedProduct">
                        <input type="hidden" name="selected_promo" x-model="selectedPromo">
                        
                        <div class="flex flex-col xl:flex-row gap-6 items-start">
                            <!-- Kolom Tengah: Tujuan & Nominal -->
                            <div class="w-full xl:w-7/12 space-y-6">
                                <!-- Step 1: Player ID / Email -->
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-lg rounded-2xl p-5">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">1</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                            @if($isApp)
                                                Masukkan Data Tujuan (Email)
                                            @else
                                                Masukkan Tujuan
                                            @endif
                                        </h3>
                                    </div>

                                    @if($isRequiresZone)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5">
                                                    @if($isApp)
                                                        <i class="fas fa-envelope text-indigo-500"></i>
                                                    @endif
                                                    {{ $field1Label }}
                                                </label>
                                                <input type="{{ $isApp ? 'email' : 'text' }}" name="player_id" x-model="playerId" 
                                                    placeholder="{{ $isApp ? 'Masukkan alamat email aktif' : 'Contoh: 12345678' }}" required
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm p-3 font-semibold text-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">
                                                    {{ $field2Label }}
                                                </label>
                                                <input type="text" name="zone_id" x-model="zoneId" 
                                                    placeholder="Contoh: 1234" required
                                                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm p-3 font-semibold text-sm">
                                            </div>
                                        </div>
                                    @else
                                        <!-- Full Width Single Input (Memanjang Sampai Kanan Penuh) -->
                                        <div class="w-full">
                                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5 flex items-center gap-1.5">
                                                @if($isApp)
                                                    <i class="fas fa-envelope text-indigo-500"></i>
                                                @else
                                                    <i class="fas fa-user text-indigo-500"></i>
                                                @endif
                                                {{ $field1Label }}
                                            </label>
                                            <input type="{{ $isApp ? 'email' : 'text' }}" name="player_id" x-model="playerId" 
                                                placeholder="{{ $isApp ? 'Masukkan alamat email aktif (contoh: nama@gmail.com)' : ($game->slug == 'valorant' ? 'Contoh: RiotID#1234' : 'Masukkan ' . $field1Label) }}" required
                                                class="w-full block rounded-xl border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm p-3 font-semibold text-sm">
                                        </div>
                                    @endif

                                    <!-- Keterangan Tambahan di Bawah Input -->
                                    <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 flex items-center gap-1.5">
                                        @if(!empty($game->target_field_1_help))
                                            <span>💡</span> <em>{{ $game->target_field_1_help }}</em>
                                        @elseif($isApp)
                                            <span>📧</span> <em>Data akun / link undangan resmi akan otomatis tampil di layar dan tertera pada invoice pesanan.</em>
                                        @else
                                            <span>💡</span> <em>Pastikan data yang Anda masukkan valid agar pesanan langsung masuk otomatis.</em>
                                        @endif
                                    </p>
                                </div>

                                 <!-- Step 2: Nominal -->
                                 <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-lg rounded-2xl p-5">
                                     @php
                                         $isFirstUserEligible = !empty($isFirstTime) && !empty($promoSettings['first_user_active']) && \App\Helpers\PromoHelper::isCategoryEligible($game, $promoSettings['first_user_categories']);
                                         $isDayPromoEligible = !empty($dayCheck['active']) && \App\Helpers\PromoHelper::isCategoryEligible($game, $promoSettings['day_promo_categories']);
                                     @endphp

                                     <div class="flex items-center justify-between mb-4">
                                         <div class="flex items-center gap-3">
                                             <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                                             <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Nominal</h3>
                                         </div>
                                         @if($isDayPromoEligible)
                                             <span class="px-3.5 py-1 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-xs font-black rounded-full shadow-md animate-pulse flex items-center gap-1.5">
                                                 <span>🔥</span> Promo Hari {{ $dayCheck['day_name'] }}
                                             </span>
                                         @elseif($isFirstUserEligible)
                                             <span class="px-3.5 py-1 bg-gradient-to-r from-pink-500 to-indigo-600 text-white text-xs font-black rounded-full shadow-md flex items-center gap-1.5">
                                                 <span>🎁</span> Diskon Akun Baru
                                             </span>
                                         @endif
                                     </div>

                                     <!-- Promo Alert Box (High Contrast & Glowing) -->
                                     @if($isDayPromoEligible)
                                         <div class="mb-5 p-4 rounded-2xl bg-gradient-to-r from-emerald-500/15 via-teal-500/10 to-emerald-500/15 dark:from-emerald-950/70 dark:via-teal-950/70 dark:to-emerald-950/70 border-2 border-emerald-500/50 shadow-md">
                                             <div class="flex items-start gap-3">
                                                 <div class="w-9 h-9 rounded-xl bg-emerald-500/20 flex items-center justify-center text-xl shrink-0">
                                                     🔥
                                                 </div>
                                                 <div class="flex-1">
                                                     <h5 class="font-black text-xs sm:text-sm text-emerald-700 dark:text-emerald-300 uppercase tracking-wide">
                                                         {{ $promoSettings['promo_day_title'] ?: 'Promo Spesial Hari '.$dayCheck['day_name'] }}
                                                     </h5>
                                                     <p class="text-xs text-gray-700 dark:text-gray-200 font-medium mt-0.5 leading-relaxed">
                                                         Nikmati potongan <strong class="text-amber-500 dark:text-amber-300 font-black text-sm">{{ $promoSettings['day_promo_type'] === 'percent' ? $promoSettings['day_promo_value'].'%' : 'Rp'.number_format($promoSettings['day_promo_value'], 0, ',', '.') }}</strong> otomatis saat checkout!
                                                         @if($promoSettings['day_promo_min_spend'] > 0)
                                                             <span class="text-gray-500 dark:text-gray-400 text-[11px] font-normal">(*Min. belanja Rp{{ number_format($promoSettings['day_promo_min_spend'], 0, ',', '.') }})</span>
                                                         @endif
                                                     </p>
                                                 </div>
                                             </div>
                                         </div>
                                     @elseif($isFirstUserEligible)
                                         <div class="mb-5 p-4 rounded-2xl bg-gradient-to-r from-pink-500/15 via-purple-500/10 to-indigo-500/15 dark:from-pink-950/70 dark:via-purple-950/70 dark:to-indigo-950/70 border-2 border-pink-500/50 shadow-md">
                                             <div class="flex items-start gap-3">
                                                 <div class="w-9 h-9 rounded-xl bg-pink-500/20 flex items-center justify-center text-xl shrink-0">
                                                     🎁
                                                 </div>
                                                 <div class="flex-1">
                                                     <h5 class="font-black text-xs sm:text-sm text-pink-600 dark:text-pink-300 uppercase tracking-wide">
                                                         {{ $promoSettings['first_user_title'] ?: 'Diskon Spesial Pengguna Baru' }}
                                                     </h5>
                                                     <p class="text-xs text-gray-700 dark:text-gray-200 font-medium mt-0.5 leading-relaxed">
                                                         Dapatkan diskon <strong class="text-amber-500 dark:text-amber-300 font-black text-sm">{{ $promoSettings['first_user_type'] === 'percent' ? $promoSettings['first_user_value'].'%' : 'Rp'.number_format($promoSettings['first_user_value'], 0, ',', '.') }}</strong> khusus transaksi pertama akun Anda!
                                                         @if($promoSettings['first_user_min_spend'] > 0)
                                                             <span class="text-gray-500 dark:text-gray-400 text-[11px] font-normal">(*Min. belanja Rp{{ number_format($promoSettings['first_user_min_spend'], 0, ',', '.') }})</span>
                                                         @endif
                                                     </p>
                                                 </div>
                                             </div>
                                         </div>
                                     @endif
                                     
                                     @foreach($categories as $cat => $catProducts)
                                         <h4 class="font-black text-gray-900 dark:text-white mb-3 mt-6 border-b border-gray-200 dark:border-gray-700 pb-2 text-sm sm:text-base flex items-center gap-2">
                                             <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                                             <span>{{ $cat }}</span>
                                         </h4>
                                          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
                                             @foreach($catProducts as $product)
                                                 @php
                                                     $prodCalc = \App\Helpers\PromoHelper::calculateDiscount(auth()->user(), (float)$product->price_sell, $game, (float)$product->price_modal);
                                                 @endphp
                                                 <div @click="if(!stockMap['{{ $product->id }}']) selectedProduct = '{{ $product->id }}'"
                                                      data-product-id="{{ $product->id }}"
                                                      data-product-name="{{ $product->name }}"
                                                      data-product-price="Rp{{ number_format($prodCalc['final_amount'], 0, ',', '.') }}"
                                                      data-product-original-price="Rp{{ number_format($product->price_sell, 0, ',', '.') }}"
                                                      :class="{
                                                          'border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-100/50 dark:bg-gray-800/40 opacity-40 cursor-not-allowed select-none': stockMap['{{ $product->id }}'],
                                                          'border-2 border-indigo-500 bg-indigo-50/60 dark:bg-gray-900 shadow-lg shadow-indigo-500/20 ring-2 ring-indigo-500/50 scale-[1.02] cursor-pointer': !stockMap['{{ $product->id }}'] && selectedProduct == '{{ $product->id }}',
                                                          'border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-indigo-400 dark:hover:border-indigo-500 hover:bg-indigo-50/30 dark:hover:bg-gray-700/60 shadow-sm hover:shadow-md hover:scale-[1.01] cursor-pointer': !stockMap['{{ $product->id }}'] && selectedProduct != '{{ $product->id }}'
                                                      }"
                                                      class="relative rounded-2xl p-4 transition-all duration-200 text-center flex flex-col justify-between min-h-[115px] overflow-hidden group">
                                                     
                                                     <!-- Active Indicator Checkmark -->
                                                     <template x-if="selectedProduct == '{{ $product->id }}'">
                                                         <div class="absolute top-2 right-2 w-5 h-5 bg-indigo-600 text-white rounded-full flex items-center justify-center text-[10px] font-black shadow-sm">
                                                             <i class="fas fa-check"></i>
                                                         </div>
                                                     </template>

                                                     <div :class="stockMap['{{ $product->id }}'] ? 'text-gray-400 dark:text-gray-500 line-through' : 'text-gray-900 dark:text-white'" 
                                                          class="text-xs sm:text-sm font-bold leading-snug mb-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">
                                                         {{ $product->name }}
                                                     </div>
                                                     
                                                     <div class="space-y-1 mt-auto">
                                                         @if($prodCalc['has_discount'])
                                                             <!-- Coretan Harga Asli -->
                                                             <div class="text-[11px] text-gray-400 dark:text-gray-400 font-semibold line-through font-mono">
                                                                 Rp{{ number_format($product->price_sell, 0, ',', '.') }}
                                                             </div>
                                                             <!-- Harga Promo Menonjol & Jelas -->
                                                             <div class="text-sm sm:text-base font-black text-amber-500 dark:text-amber-300 font-mono tracking-tight">
                                                                 Rp{{ number_format($prodCalc['final_amount'], 0, ',', '.') }}
                                                             </div>
                                                             <!-- Badge Hemat -->
                                                             <div class="pt-0.5">
                                                                 <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-black bg-gradient-to-r from-pink-500 to-rose-600 text-white shadow-sm">
                                                                     ⚡ {{ $prodCalc['savings_text'] }}
                                                                 </span>
                                                             </div>
                                                         @else
                                                             <!-- Harga Normal -->
                                                             <div :class="stockMap['{{ $product->id }}'] ? 'text-gray-400' : 'text-indigo-600 dark:text-indigo-400'" class="text-xs sm:text-sm font-black font-mono">
                                                                 Rp{{ number_format($product->price_sell, 0, ',', '.') }}
                                                             </div>
                                                         @endif

                                                         <template x-if="stockMap['{{ $product->id }}']">
                                                             <span class="inline-block text-[10px] font-black text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-950/80 px-2 py-0.5 rounded-md border border-red-200 dark:border-red-800/60 mt-1">Stok Habis</span>
                                                         </template>
                                                     </div>
                                                 </div>
                                             @endforeach
                                          </div>
                                      @endforeach
                                  </div>
                              </div>

                            <!-- Kolom Kanan: Promo & Metode Pembayaran -->
                            <div class="w-full xl:w-5/12 space-y-4" style="position: sticky; top: 5rem; align-self: flex-start; max-height: calc(100vh - 5.5rem); overflow-y: auto;">
                                
                                <!-- Box Pilihan Promo Pelanggan (Muncul jika ada promo yang memenuhi syarat) -->
                                <div x-show="selectedProduct && getAvailablePromos().length > 0"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl rounded-2xl p-4 space-y-2.5">
                                    <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-6 h-6 rounded-full bg-amber-400 text-gray-950 flex items-center justify-center text-xs font-black shadow-sm">
                                                <i class="fas fa-gift"></i>
                                            </span>
                                            <h4 class="text-xs sm:text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider">
                                                Pilih Promo Diskon
                                            </h4>
                                        </div>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            Pilih 1 promo
                                        </span>
                                    </div>

                                    <div class="space-y-2 pt-0.5">
                                        <!-- Loop Tiap Promo yang Memenuhi Syarat -->
                                        <template x-for="promo in getAvailablePromos()" :key="promo.type">
                                            <label class="flex items-center justify-between p-2.5 sm:p-3 border-2 rounded-xl cursor-pointer shadow-sm transition"
                                                   :class="(selectedPromo === promo.type || (selectedPromo === 'auto' && promo.type === productPromoMap[selectedProduct]?.default_promo_type)) 
                                                       ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/80 dark:bg-gray-900 ring-2 ring-indigo-500/20 text-gray-900 dark:text-white' 
                                                       : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600 text-gray-900 dark:text-gray-200'">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="promo_choice" :value="promo.type"
                                                           :checked="selectedPromo === promo.type || (selectedPromo === 'auto' && promo.type === productPromoMap[selectedProduct]?.default_promo_type)"
                                                           @change="selectedPromo = promo.type"
                                                           class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                                    <div>
                                                        <div class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white" x-text="promo.title"></div>
                                                        <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-bold" x-text="promo.savings_text"></div>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-amber-300 border border-gray-200 dark:border-gray-600 font-mono shadow-sm" x-text="'Rp' + Number(promo.final_amount).toLocaleString('id-ID')"></span>
                                                </div>
                                            </label>
                                        </template>

                                        <!-- Opsi Tidak Memakai Promo (Simpan Promo) -->
                                        <label class="flex items-center justify-between p-2 rounded-xl border cursor-pointer transition text-xs"
                                               :class="selectedPromo === 'none' 
                                                   ? 'border-gray-400 dark:border-gray-600 bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white font-bold' 
                                                   : 'border-transparent text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                                            <div class="flex items-center gap-2.5">
                                                <input type="radio" name="promo_choice" value="none"
                                                       :checked="selectedPromo === 'none'"
                                                       @change="selectedPromo = 'none'"
                                                       class="w-4 h-4 text-gray-400 focus:ring-gray-400 cursor-pointer">
                                                <span class="text-[11px] text-gray-700 dark:text-gray-300">Jangan gunakan promo (simpan untuk nanti)</span>
                                            </div>
                                            <span class="text-[11px] font-mono font-bold text-gray-500 dark:text-gray-400" x-text="selectedProduct && productPromoMap[selectedProduct] ? productPromoMap[selectedProduct].original_formatted : ''"></span>
                                        </label>
                                    </div>
                                </div>

                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl rounded-2xl p-4">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="w-7 h-7 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-md shadow-indigo-500/20">3</div>
                                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Metode Pembayaran</h3>
                                    </div>
                                    <div class="space-y-2.5">
                                        <!-- QRIS (Default) -->
                                        <label class="relative flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer shadow-sm transition"
                                               :class="selectedPayment === 'qris' ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/80 dark:bg-gray-900 ring-2 ring-indigo-500/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="payment_method" x-model="selectedPayment" value="qris" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 cursor-pointer shrink-0">
                                                <span class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white tracking-wide">QRIS All Payment</span>
                                            </div>
                                            <div class="bg-white p-1 rounded-lg shadow-sm border border-gray-200 shrink-0 ml-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-5 object-contain">
                                            </div>
                                        </label>

                                        <!-- Saldo Akun / Wallet -->
                                        @php
                                            $userBalance = auth()->check() ? (float)auth()->user()->balance : 0;
                                        @endphp
                                        <label class="relative flex items-center justify-between p-3 border-2 rounded-xl cursor-pointer shadow-sm transition"
                                               :class="selectedPayment === 'balance' ? 'border-emerald-600 dark:border-emerald-500 bg-emerald-50/80 dark:bg-gray-900 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" name="payment_method" x-model="selectedPayment" value="balance" class="w-4 h-4 text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer shrink-0">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-xs sm:text-sm text-gray-900 dark:text-white tracking-wide">Saldo Akun</span>
                                                    @auth
                                                        <span class="px-2 py-0.5 bg-emerald-400 text-gray-950 font-black text-[10px] rounded-full shadow-sm tracking-wide">Rp{{ number_format($userBalance, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-amber-400 text-gray-950 font-bold text-[10px] rounded-full shadow-sm">Perlu Login</span>
                                                    @endauth
                                                </div>
                                            </div>
                                            <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-sm shadow-sm border border-emerald-200 dark:border-emerald-700 shrink-0 ml-3">
                                                <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Ringkasan Total Tagihan Dinamis -->
                                <div x-show="selectedProduct" x-transition class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex justify-between items-center shadow-sm">
                                    <div>
                                        <span class="text-gray-500 dark:text-gray-400 block text-[11px] font-semibold">Total Pembayaran:</span>
                                        <span class="text-base sm:text-lg font-black font-mono text-indigo-600 dark:text-indigo-400" x-text="getCurrentPriceFormatted()"></span>
                                    </div>
                                    <template x-if="selectedPromo !== 'none' && selectedProduct && productPromoMap[selectedProduct]?.has_discount">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                            <i class="fas fa-check-circle mr-1"></i> Promo Aktif
                                        </span>
                                    </template>
                                </div>
                                
                                <button type="submit" class="w-full py-3.5 rounded-xl font-black text-white text-sm shadow-xl transition-all transform hover:scale-[1.01] active:scale-98 flex items-center justify-center gap-2"
                                        :class="selectedProduct ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/40 cursor-pointer' : 'bg-gray-400 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed opacity-75'"
                                        :disabled="!selectedProduct">
                                    <span>Beli Sekarang</span>
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const form = document.getElementById('topup-form');
        const currentUserBalance = {{ auth()->check() ? (float)auth()->user()->balance : 0 }};
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            // JIKA USER BELUM LOGIN: TAMPILKAN MODAL LOGIN SEBELUM MELANJUTKAN PEMBAYARAN
            @guest
                sessionStorage.setItem('totap_auto_submit_{{ $game->slug }}', '1');
                Swal.fire({
                    icon: 'info',
                    title: '<span class="text-base sm:text-lg font-black text-gray-900 dark:text-white">Silakan Masuk Terlebih Dahulu</span>',
                    text: 'Untuk keamanan transaksi, invoice resmi, dan jaminan saldo refund, silakan Masuk atau Buat Akun terlebih dahulu.',
                    confirmButtonText: 'Masuk / Daftar Akun 🔐',
                    confirmButtonColor: '#4f46e5',
                    customClass: {
                        popup: 'rounded-3xl dark:bg-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 shadow-2xl'
                    }
                }).then(() => {
                    if (typeof openLoginModal === 'function') {
                        openLoginModal();
                    } else {
                        const modal = document.getElementById('modal-login-backdrop');
                        if (modal) modal.classList.remove('hidden');
                    }
                });
                return;
            @endguest
            
            const formData = new FormData(form);
            if (!formData.get('product_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih nominal top up terlebih dahulu!' });
                return;
            }
            if (!formData.get('player_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Masukkan ID Anda terlebih dahulu!' });
                return;
            }

            const payMethodVal = formData.get('payment_method') || 'qris';
            const prodEl = document.querySelector(`[data-product-id="${formData.get('product_id')}"]`);
            const productName = prodEl ? prodEl.getAttribute('data-product-name') : 'Item Game';
            const productPrice = prodEl ? prodEl.getAttribute('data-product-price') : '';
            const productRawPrice = prodEl ? parseInt(productPrice.replace(/[^0-9]/g, '')) : 0;

            if (payMethodVal === 'balance' && currentUserBalance < productRawPrice) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Saldo Akun Tidak Cukup!',
                    text: 'Saldo akun Anda saat ini (Rp' + currentUserBalance.toLocaleString('id-ID') + ') tidak mencukupi untuk nominal ' + productPrice + '. Silakan pilih metode QRIS All Payment.',
                    confirmButtonColor: '#4f46e5',
                    confirmButtonText: 'Pilih QRIS'
                });
                return;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Mengecek ID... <i class="fas fa-spinner fa-spin ml-2"></i>';
            submitBtn.disabled = true;
            
            fetch('{{ route("topup.check-nickname", $game->slug) }}', {
                method: 'POST',
                body: formData,
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                // 1. JIKA ID BENAR (result: true): TAMPILKAN MODAL KONFIRMASI PESANAN DENGAN NICKNAME!
                if (data.result === true) {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;

                    let accountRowHtml = '';
                    if (data.is_checked && data.nickname) {
                        accountRowHtml = `
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">Nama Akun (IGN):</span>
                                <span class="font-black text-emerald-600 dark:text-emerald-400">${data.nickname}</span>
                            </div>
                        `;
                    }

                    let idRowsHtml = '';
                    if (formData.get('zone_id')) {
                        idRowsHtml = `
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">User ID:</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">${formData.get('player_id')}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">Zone ID:</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">${formData.get('zone_id')}</span>
                            </div>
                        `;
                    } else {
                        idRowsHtml = `
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">ID / Target:</span>
                                <span class="font-mono font-bold text-gray-900 dark:text-white">${formData.get('player_id')}</span>
                            </div>
                        `;
                    }

                    const payMethodDisplay = payMethodVal === 'balance' ? 'Saldo Akun (Dompet Web)' : 'QRIS All Payment';

                    let priceBreakdownHtml = '';
                    if (data.discount_info && data.discount_info.has_discount) {
                        priceBreakdownHtml = `
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                <span class="text-gray-500 dark:text-gray-400 font-semibold">Harga Asli:</span>
                                <span class="font-bold text-gray-500 dark:text-gray-400 line-through">Rp${Number(data.discount_info.original_amount).toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700 text-emerald-600 dark:text-emerald-400">
                                <span class="font-bold flex items-center gap-1">🎁 ${data.discount_info.promo_title}:</span>
                                <span class="font-black font-mono">- Rp${Number(data.discount_info.discount_amount).toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-900 dark:text-white font-black text-sm">Total Pembayaran:</span>
                                <span class="font-black text-base text-emerald-600 dark:text-emerald-400 font-mono">Rp${Number(data.discount_info.final_amount).toLocaleString('id-ID')}</span>
                            </div>
                        `;
                    } else {
                        priceBreakdownHtml = `
                            <div class="flex justify-between items-center pt-2">
                                <span class="text-gray-700 dark:text-gray-300 font-bold">Total Tagihan:</span>
                                <span class="font-black text-sm text-emerald-600 dark:text-emerald-400">${productPrice}</span>
                            </div>
                        `;
                    }

                    Swal.fire({
                        title: '<span class="text-lg font-black text-gray-900 dark:text-white">Konfirmasi Data Pesanan</span>',
                        html: `
                            <div class="text-left text-xs space-y-2.5 p-4 rounded-2xl bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 my-2">
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-semibold">Game:</span>
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $game->name }}</span>
                                </div>
                                ${accountRowHtml}
                                ${idRowsHtml}
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-semibold">Item:</span>
                                    <span class="font-bold text-indigo-600 dark:text-indigo-400">${productName}</span>
                                </div>
                                <div class="flex justify-between items-center py-1.5 border-b border-gray-200 dark:border-gray-700">
                                    <span class="text-gray-500 dark:text-gray-400 font-semibold">Metode Bayar:</span>
                                    <span class="font-bold text-gray-900 dark:text-white">${payMethodDisplay}</span>
                                </div>
                                ${priceBreakdownHtml}
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 text-center mt-2">Pastikan data akun dan item sudah sesuai sebelum melanjutkan pembayaran.</p>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Lanjutkan Pembayaran 👉',
                        cancelButtonText: 'Batal / Ubah',
                        confirmButtonColor: '#4f46e5',
                        cancelButtonColor: '#6b7280',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-3xl dark:bg-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 shadow-2xl'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            submitBtn.innerHTML = 'Memproses Pesanan... <i class="fas fa-spinner fa-spin ml-2"></i>';
                            submitBtn.disabled = true;
                            
                            sessionStorage.removeItem('totap_product_{{ $game->slug }}');
                            sessionStorage.removeItem('totap_payment_{{ $game->slug }}');
                            sessionStorage.removeItem('totap_player_id_{{ $game->slug }}');
                            sessionStorage.removeItem('totap_zone_id_{{ $game->slug }}');
                            sessionStorage.removeItem('totap_auto_submit_{{ $game->slug }}');
                            
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    });
                } else {
                    // 2. JIKA ID SALAH (result: false): BLOKIR DAN BERITAHU
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    let errorMsg = data.message || 'Player ID / Tagline tidak valid atau tidak ditemukan.';
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'ID Game Tidak Ditemukan!',
                        text: errorMsg,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Periksa Kembali',
                        customClass: {
                            popup: 'rounded-3xl dark:bg-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 shadow-2xl'
                        }
                    });
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Memeriksa ID',
                    text: 'Terjadi gangguan jaringan saat memvalidasi ID. Silakan coba beberapa saat lagi.',
                    confirmButtonColor: '#4f46e5'
                });
            });
        });
    </script>
</x-app-layout>
