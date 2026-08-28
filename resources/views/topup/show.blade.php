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
                                <div class="w-20 h-20 bg-indigo-600/20 text-indigo-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-gamepad text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-black text-xl text-gray-900 dark:text-white">{{ $game->name }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $game->developer ?? 'Moonton' }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                {{ $game->description ?? ($isApp ? 'Langganan ' . $game->name . ' proses cepat dan otomatis. Masukkan alamat email Anda, pilih paket langganan, dan selesaikan pembayaran.' : 'Top up ' . $game->name . ' proses cepat dan otomatis. Silakan masukkan User ID akun Anda, pilih nominal, dan selesaikan pembayaran.') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Form Transaksi -->
                <div class="w-full lg:w-3/4" x-data="{
                    selectedProduct: sessionStorage.getItem('totap_product_{{ $game->slug }}') || null,
                    selectedPayment: sessionStorage.getItem('totap_payment_{{ $game->slug }}') || 'qris',
                    playerId: sessionStorage.getItem('totap_player_id_{{ $game->slug }}') || '',
                    zoneId: sessionStorage.getItem('totap_zone_id_{{ $game->slug }}') || '',
                    stockMap: {{ json_encode($stockMap ?? []) }},
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
                        this.$watch('selectedProduct', v => sessionStorage.setItem('totap_product_{{ $game->slug }}', v || ''));
                        this.$watch('selectedPayment', v => sessionStorage.setItem('totap_payment_{{ $game->slug }}', v || ''));
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
                                     <div class="flex items-center justify-between mb-4">
                                         <div class="flex items-center gap-3">
                                             <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                                             <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Nominal</h3>
                                         </div>
                                         @if(!empty($dayCheck['active']))
                                             <span class="px-3 py-1 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-[11px] font-black rounded-full shadow-sm animate-pulse flex items-center gap-1">
                                                 🔥 Promo Hari {{ $dayCheck['day_name'] }}
                                             </span>
                                         @elseif(!empty($isFirstTime) && !empty($promoSettings['first_user_active']))
                                             <span class="px-3 py-1 bg-gradient-to-r from-pink-600 to-indigo-600 text-white text-[11px] font-black rounded-full shadow-sm flex items-center gap-1">
                                                 🎁 Diskon Akun Baru
                                             </span>
                                         @endif
                                     </div>

                                     @if(!empty($dayCheck['active']))
                                         <div class="mb-4 p-3 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 rounded-xl text-xs text-emerald-800 dark:text-emerald-300 font-semibold flex items-center gap-2">
                                             <span class="text-base">🎉</span>
                                             <span><strong>{{ $promoSettings['promo_day_title'] ?: 'Promo Hari '.$dayCheck['day_name'] }}:</strong> Dapatkan diskon {{ $promoSettings['day_promo_type'] === 'percent' ? $promoSettings['day_promo_value'].'%' : 'Rp'.number_format($promoSettings['day_promo_value'], 0, ',', '.') }} otomatis saat checkout!</span>
                                         </div>
                                     @elseif(!empty($isFirstTime) && !empty($promoSettings['first_user_active']))
                                         <div class="mb-4 p-3 bg-pink-50 dark:bg-pink-950/40 border border-pink-300 dark:border-pink-800 rounded-xl text-xs text-pink-800 dark:text-pink-300 font-semibold flex items-center gap-2">
                                             <span class="text-base">🎁</span>
                                             <span><strong>Spesial Pengguna Baru:</strong> Dapatkan potongan {{ $promoSettings['first_user_type'] === 'percent' ? $promoSettings['first_user_value'].'%' : 'Rp'.number_format($promoSettings['first_user_value'], 0, ',', '.') }} untuk transaksi pertama Anda!</span>
                                         </div>
                                     @endif
                                     
                                     @foreach($categories as $cat => $catProducts)
                                         <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-3 mt-6 border-b border-gray-200 dark:border-gray-700 pb-2">{{ $cat }}</h4>
                                          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                             @foreach($catProducts as $product)
                                                 @php
                                                     $prodCalc = \App\Helpers\PromoHelper::calculateDiscount(auth()->user(), (float)$product->price_sell);
                                                 @endphp
                                                 <div @click="if(!stockMap['{{ $product->id }}']) selectedProduct = '{{ $product->id }}'"
                                                      data-product-id="{{ $product->id }}"
                                                      data-product-name="{{ $product->name }}"
                                                      data-product-price="Rp{{ number_format($prodCalc['final_amount'], 0, ',', '.') }}"
                                                      data-product-original-price="Rp{{ number_format($product->price_sell, 0, ',', '.') }}"
                                                      :class="{
                                                          'border-2 border-dashed border-gray-200 dark:border-gray-700/80 bg-gray-50/80 dark:bg-gray-800/30 opacity-50 cursor-not-allowed select-none': stockMap['{{ $product->id }}'],
                                                          'border-2 border-indigo-600 bg-indigo-50/90 dark:bg-indigo-900/50 shadow-md ring-2 ring-indigo-500/20 scale-[1.02] cursor-pointer': !stockMap['{{ $product->id }}'] && selectedProduct == '{{ $product->id }}',
                                                          'border-2 border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/40 shadow-sm hover:scale-[1.02] cursor-pointer': !stockMap['{{ $product->id }}'] && selectedProduct != '{{ $product->id }}'
                                                      }"
                                                      class="relative rounded-xl p-3.5 transition-all text-center">
                                                     <div :class="stockMap['{{ $product->id }}'] ? 'text-gray-500 dark:text-gray-400 line-through' : 'text-gray-900 dark:text-white'" class="text-sm font-bold leading-tight mb-1.5">{{ $product->name }}</div>
                                                     
                                                     @if($prodCalc['has_discount'])
                                                         <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                                             <span class="text-[11px] text-gray-400 line-through">Rp{{ number_format($product->price_sell, 0, ',', '.') }}</span>
                                                             <span class="text-xs font-black text-emerald-600 dark:text-emerald-400">Rp{{ number_format($prodCalc['final_amount'], 0, ',', '.') }}</span>
                                                         </div>
                                                         <span class="inline-block text-[9px] font-black text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-950 px-2 py-0.5 rounded-full border border-emerald-300 dark:border-emerald-800 mt-1">
                                                             {{ $prodCalc['savings_text'] }}
                                                         </span>
                                                     @else
                                                         <div :class="stockMap['{{ $product->id }}'] ? 'text-gray-400' : 'text-indigo-600 dark:text-indigo-400'" class="text-xs font-black">Rp{{ number_format($product->price_sell, 0, ',', '.') }}</div>
                                                     @endif

                                                     <template x-if="stockMap['{{ $product->id }}']">
                                                         <span class="inline-block text-[10px] font-black text-red-600 dark:text-red-400 bg-red-100 dark:bg-red-950/80 px-2 py-0.5 rounded-md border border-red-200 dark:border-red-800/60 mt-1">Stok Habis</span>
                                                     </template>
                                                 </div>
                                             @endforeach
                                          </div>
                                      @endforeach
                                  </div>
                              </div>

                            <!-- Kolom Kanan: Metode Pembayaran -->
                            <div class="w-full xl:w-5/12 space-y-6" style="position: sticky; top: 6rem; align-self: flex-start;">
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl rounded-2xl p-5">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold shadow-md shadow-indigo-500/20">3</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Metode Pembayaran</h3>
                                    </div>
                                    <div class="space-y-3">
                                        <!-- QRIS (Default) -->
                                        <label class="relative flex items-center justify-between p-4 sm:p-4.5 border-2 rounded-2xl cursor-pointer shadow-sm transition"
                                               :class="selectedPayment === 'qris' ? 'border-indigo-600 dark:border-indigo-500 bg-indigo-50/80 dark:bg-gray-900 ring-2 ring-indigo-500/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600'">
                                            <div class="flex items-center gap-4">
                                                <input type="radio" name="payment_method" x-model="selectedPayment" value="qris" class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500 cursor-pointer shrink-0">
                                                <span class="font-bold text-sm sm:text-base text-gray-900 dark:text-white tracking-wide">QRIS All Payment</span>
                                            </div>
                                            <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 shrink-0 ml-3">
                                                <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain">
                                            </div>
                                        </label>

                                        <!-- Saldo Akun / Wallet -->
                                        @php
                                            $userBalance = auth()->check() ? (float)auth()->user()->balance : 0;
                                        @endphp
                                        <label class="relative flex items-center justify-between p-4 sm:p-4.5 border-2 rounded-2xl cursor-pointer shadow-sm transition"
                                               :class="selectedPayment === 'balance' ? 'border-emerald-600 dark:border-emerald-500 bg-emerald-50/80 dark:bg-gray-900 ring-2 ring-emerald-500/20' : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800/80 hover:border-gray-300 dark:hover:border-gray-600'">
                                            <div class="flex items-center gap-4">
                                                <input type="radio" name="payment_method" x-model="selectedPayment" value="balance" class="w-5 h-5 text-emerald-600 border-gray-300 focus:ring-emerald-500 cursor-pointer shrink-0">
                                                <div class="flex items-center gap-2.5">
                                                    <span class="font-bold text-sm sm:text-base text-gray-900 dark:text-white tracking-wide">Saldo Akun</span>
                                                    @auth
                                                        <span class="px-2.5 py-0.5 bg-emerald-400 text-gray-950 font-black text-xs rounded-full shadow-sm tracking-wide">Rp{{ number_format($userBalance, 0, ',', '.') }}</span>
                                                    @else
                                                        <span class="px-2.5 py-0.5 bg-amber-400 text-gray-950 font-bold text-xs rounded-full shadow-sm">Perlu Login</span>
                                                    @endauth
                                                </div>
                                            </div>
                                            <div class="w-9 h-9 rounded-xl bg-emerald-100 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 flex items-center justify-center text-base shadow-sm border border-emerald-200 dark:border-emerald-700 shrink-0 ml-3">
                                                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full py-3.5 rounded-xl font-bold text-white shadow-lg transition-all transform hover:scale-[1.02]"
                                        :class="selectedProduct ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-600/30 cursor-pointer' : 'bg-gray-400 dark:bg-gray-700 dark:text-gray-400 cursor-not-allowed opacity-75'"
                                        :disabled="!selectedProduct">
                                    Beli Sekarang
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
