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
        <div class="flex items-center gap-4">
            <a href="{{ url('/topup') }}" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium text-gray-300 bg-gray-800 hover:bg-gray-700 hover:text-white rounded-lg border border-gray-700 transition-all shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight border-l-2 border-gray-600 pl-4">
                Top Up {{ $game->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-xl mb-6 shadow-md flex items-center gap-3">
                    <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <div>
                        <strong class="font-bold">Gagal: </strong> {{ session('error') }}
                    </div>
                </div>
            @endif
            @if(session('success'))
                <div class="bg-green-500/20 border border-green-500 text-green-200 p-4 rounded-xl mb-6 shadow-md flex items-center gap-3">
                    <svg class="w-6 h-6 text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    <div>{{ session('success') }}</div>
                </div>
            @endif
            <!-- Panduan Top Up -->
            <div class="bg-gray-800 border-l-4 border-blue-500 rounded-xl p-5 mb-8 shadow-md" data-aos="fade-down">
                <h3 class="font-bold text-lg mb-3 text-white flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Cara Top Up {{ $game->name }}
                </h3>
                <ul class="space-y-2 text-sm md:text-base text-gray-300">
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">1.</span>
                        <div>
                            <span>Masukkan data target 
                            @if($game->requires_zone_id)
                                (<strong>{{ $game->target_field_1 }}</strong> dan <strong>{{ $game->target_field_2 ?? 'Zone ID' }}</strong>) 
                            @else
                                (<strong>{{ $game->target_field_1 }}</strong>) 
                            @endif
                            yang sesuai dengan akun {{ $game->name }} Anda.</span>
                            
                            @php
                                $gameName = strtolower($game->name);
                            @endphp
                            
                            @if(str_contains($gameName, 'mobile legend'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 12345678 untuk Player ID dan 1234 untuk Zone ID.</div>
                            @elseif(str_contains($gameName, 'valorant'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: Jett (Riot ID) dan 1234 (Tagline tanpa #).</div>
                            @elseif(str_contains($gameName, 'free fire'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 1234567890 (Temukan di profil game).</div>
                            @elseif(str_contains($gameName, 'genshin'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 800123456 (Server Asia).</div>
                            @elseif(str_contains($gameName, 'pubg'))
                                <div class="text-xs text-gray-400 mt-1 italic">Contoh: 5123456789.</div>
                            @else
                                <div class="text-xs text-gray-400 mt-1 italic">Pastikan data yang Anda masukkan valid agar pesanan tidak gagal.</div>
                            @endif
                        </div>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">2.</span>
                        <span>Pilih nominal item atau layanan yang Anda inginkan dari daftar yang tersedia.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">3.</span>
                        <span>Pilih salah satu metode pembayaran yang paling memudahkan Anda.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="font-bold text-blue-500">4.</span>
                        <span>Klik tombol <strong>Beli Sekarang</strong> dan selesaikan pembayaran. Pesanan akan masuk otomatis!</span>
                    </li>
                </ul>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Kiri: Info Game -->
                <div class="w-full lg:w-1/4">
                    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden sticky top-24">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image }}" alt="Cover" class="w-full h-64 lg:h-48 object-cover">
                        @else
                            <div class="w-full h-64 lg:h-48 bg-indigo-100 flex items-center justify-center">
                                <span class="text-indigo-400 font-bold">No Cover</span>
                            </div>
                        @endif
                        <div class="p-6">
                            <h3 class="text-xl font-black text-gray-900 dark:text-white mb-1">{{ $game->name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mb-4">{{ $game->developer ?? 'T-Store' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                {{ $game->guide_text ?? 'Top up ' . $game->name . ' proses cepat dan otomatis. Silakan masukkan Player ID Anda, pilih nominal, dan selesaikan pembayaran.' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Form Pembelian -->
                <div class="w-full lg:w-3/4 space-y-6" x-data="{ selectedProduct: null, selectedPayment: 'qris' }">
                    <form action="{{ route('topup.process', $game->slug) }}" method="POST" id="topup-form">
                        @csrf
                        <input type="hidden" name="product_id" x-model="selectedProduct">
                        <input type="hidden" name="payment_method" x-model="selectedPayment">
                        
                        <div class="flex flex-col xl:flex-row gap-6 items-start">
                            <!-- Kolom Tengah: Tujuan & Nominal -->
                            <div class="w-full xl:w-7/12 space-y-6">
                                <!-- Step 1: Player ID -->
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">1</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Masukkan Tujuan</h3>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <input type="text" name="player_id" placeholder="{{ $game->target_field_1 }}" required
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                        </div>
                                        @if($game->requires_zone_id)
                                        <div>
                                            <input type="text" name="zone_id" placeholder="{{ $game->target_field_2 ?? 'Zone ID' }}" required
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Step 2: Nominal -->
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Nominal</h3>
                                    </div>
                                    
                                    @foreach($categories as $cat => $catProducts)
                                        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-3 mt-6 border-b pb-2">{{ $cat }}</h4>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach($catProducts as $product)
                                                <div @click="selectedProduct = '{{ $product->id }}'" 
                                                     :class="selectedProduct == '{{ $product->id }}' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-200 dark:border-gray-700'"
                                                     class="relative rounded-xl border-2 p-3 cursor-pointer hover:border-indigo-400 transition-all text-center">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1">{{ $product->name }}</div>
                                                    <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Rp{{ number_format($product->price_sell, 0, ',', '.') }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Kolom Kanan: Metode Pembayaran -->
                            <div class="w-full xl:w-5/12 space-y-6 " style="position: sticky; top: 6rem; align-self: flex-start;">
                                <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-4">
                                    <div class="flex items-center gap-3 mb-4">
                                        <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">3</div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Metode Pembayaran</h3>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <!-- QRIS -->
                                        <label class="relative flex items-center justify-between px-3 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-900 transition-colors"
                                               :class="selectedPayment === 'qris' ? 'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" x-model="selectedPayment" value="qris" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">QRIS</div>
                                                    <div class="text-xs text-gray-500">GoPay, ShopeePay, DANA, OVO</div>
                                                </div>
                                            </div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">
                                        </label>

                                        <!-- BCA VA -->
                                        <label class="relative flex items-center justify-between px-3 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-900 transition-colors"
                                               :class="selectedPayment === 'bca_va' ? 'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" x-model="selectedPayment" value="bca_va" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">BCA Virtual Account</div>
                                                    <div class="text-xs text-gray-500">Transfer Bank Otomatis</div>
                                                </div>
                                            </div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">
                                        </label>

                                        <!-- BNI VA -->
                                        <label class="relative flex items-center justify-between px-3 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-900 transition-colors"
                                               :class="selectedPayment === 'bni_va' ? 'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" x-model="selectedPayment" value="bni_va" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">BNI Virtual Account</div>
                                                    <div class="text-xs text-gray-500">Transfer Bank Otomatis</div>
                                                </div>
                                            </div>
                                            <div class="h-5 px-2 bg-white rounded shadow-sm flex items-center justify-center italic font-black text-sm tracking-tighter" style="line-height: 1;"><span style="color: #F05A28;">B</span><span style="color: #005E6A;">NI</span></div>
                                        </label>
                                        
                                        <!-- BRI VA -->
                                        <label class="relative flex items-center justify-between px-3 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-900 transition-colors"
                                               :class="selectedPayment === 'bri_va' ? 'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" x-model="selectedPayment" value="bri_va" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">BRI Virtual Account</div>
                                                    <div class="text-xs text-gray-500">Transfer Bank Otomatis</div>
                                                </div>
                                            </div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2e/BRI_2020.svg" alt="BRI" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">
                                        </label>

                                        <!-- Mandiri VA -->
                                        <label class="relative flex items-center justify-between px-3 py-2 border-2 rounded-lg cursor-pointer hover:bg-gray-900 transition-colors"
                                               :class="selectedPayment === 'mandiri_va' ? 'border-blue-500 bg-gray-900' : 'border-gray-700 bg-gray-800'">
                                            <div class="flex items-center gap-3">
                                                <input type="radio" x-model="selectedPayment" value="mandiri_va" class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-bold text-gray-900 dark:text-white">Mandiri Virtual Account</div>
                                                    <div class="text-xs text-gray-500">Transfer Bank Otomatis</div>
                                                </div>
                                            </div>
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/ad/Bank_Mandiri_logo_2016.svg" alt="Mandiri" class="h-5 object-contain bg-white px-2 py-1 rounded shadow-sm">
                                        </label>
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full py-3 rounded-lg font-bold text-white shadow-lg transition-all transform hover:scale-[1.02]"
                                        :class="selectedProduct ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-gray-400 cursor-not-allowed'"
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
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            const formData = new FormData(form);
            if (!formData.get('product_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Pilih nominal top up terlebih dahulu!' });
                return;
            }
            if (!formData.get('player_id')) {
                Swal.fire({ icon: 'warning', title: 'Oops...', text: 'Masukkan ID Anda terlebih dahulu!' });
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
                // 1. JIKA ID BENAR (result: true): LANGSUNG SUBMIT TANPA POP-UP KONFIRMASI LAGI
                if (data.result === true) {
                    submitBtn.innerHTML = 'ID Valid! Mengalihkan ke Pembayaran...';
                    submitBtn.disabled = false;
                    
                    // Native HTML form submission
                    HTMLFormElement.prototype.submit.call(form);
                } else {
                    // 2. JIKA ID SALAH (result: false): BLOKIR DAN BERITAHU TANPA SURUH LANJUT
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    let errorMsg = data.message || 'Player ID / Tagline tidak valid atau tidak ditemukan.';
                    if (errorMsg.includes('tidak diizinkan') || errorMsg.includes('cURL') || errorMsg.includes('timeout')) {
                        errorMsg = 'Koneksi ke server pusat sedang sibuk. Silakan coba sesaat lagi.';
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'ID Game Tidak Valid!',
                        text: errorMsg,
                        confirmButtonColor: '#4f46e5',
                        confirmButtonText: 'Periksa Kembali'
                    });
                }
            })
            .catch(err => {
                // Fallback: Jika ada gangguan AJAX, langsung submit form ke proses transaksi
                submitBtn.innerHTML = 'Memproses Pesanan...';
                submitBtn.disabled = false;
                HTMLFormElement.prototype.submit.call(form);
            });
        });
    </script>
</x-app-layout>
