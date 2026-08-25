<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 sm:p-10 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Detail Pembayaran & Invoice</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Selesaikan pembayaran untuk memproses pesanan top up game Anda.</p>
                    </div>
                    <div>
                        <span id="status-badge" class="px-3.5 py-1.5 bg-amber-100 dark:bg-amber-950/80 text-amber-800 dark:text-amber-300 border border-amber-200 dark:border-amber-800/80 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                            Menunggu Verifikasi
                        </span>
                    </div>
                </div>
                
                <!-- Order Summary Card -->
                <div class="bg-slate-50 dark:bg-gray-900/70 rounded-2xl p-6 mb-8 border border-gray-200 dark:border-gray-700/80">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                        @if($transaction->game->thumbnail)
                        <img src="{{ $transaction->game->thumbnail }}" class="w-16 h-16 rounded-2xl object-cover shadow-md border border-gray-200 dark:border-gray-700">
                        @endif
                        <div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $transaction->game->name }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-400 font-bold text-sm">{{ $transaction->gameProduct->name }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between sm:block bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-semibold">Nomor Order ID</span>
                            <span class="font-mono text-gray-900 dark:text-white font-bold text-sm">{{ $transaction->id }}</span>
                        </div>
                        <div class="flex justify-between sm:block bg-white dark:bg-gray-800 p-3 rounded-xl border border-gray-100 dark:border-gray-700">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block font-semibold">{{ $transaction->game->target_field_1 ?: 'Player ID' }}</span>
                            <span class="text-gray-900 dark:text-white font-bold text-sm font-mono">
                                {{ $transaction->target_field_1 }}
                                @if($transaction->target_field_2)
                                <span class="text-indigo-600 dark:text-indigo-400">({{ $transaction->target_field_2 }})</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Total Amount Banner -->
                <div class="flex justify-between items-center bg-indigo-50 dark:bg-indigo-950/40 p-5 rounded-2xl border border-indigo-100 dark:border-indigo-800 mb-8">
                    <div>
                        <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider block">Total Tagihan</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Bebas biaya admin gateway (Rp0)</span>
                    </div>
                    <span class="text-3xl font-black text-indigo-600 dark:text-indigo-400 font-mono">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $paymentData = json_decode($transaction->snap_token, true);
                    $adminWa = '6281328972073';
                    $waMsg = "Halo Admin ToTap Store, saya sudah transfer untuk pesanan Top Up Game:\n\n"
                           . "• Order ID: " . $transaction->id . "\n"
                           . "• Game: " . ($transaction->game->name ?? 'Game') . "\n"
                           . "• ID Akun: " . $transaction->target_field_1 . ($transaction->target_field_2 ? " (" . $transaction->target_field_2 . ")" : "") . "\n"
                           . "• Item: " . ($transaction->gameProduct->name ?? '-') . "\n"
                           . "• Total: Rp " . number_format($transaction->amount, 0, ',', '.') . "\n\n"
                           . "Mohon segera di-ACC dan diproses ya min, berikut bukti transfernya. Terima kasih!";
                    $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($waMsg);
                @endphp
                
                <div class="bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 mb-6 text-center border-2 border-indigo-500/30 dark:border-indigo-500/20 shadow-lg">
                    <!-- QRIS Header -->
                    <div class="flex items-center justify-center gap-2 mb-2">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-7 object-contain">
                    </div>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-6">
                        Scan & Transfer tepat <span class="font-bold text-indigo-600 dark:text-indigo-400 font-mono">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span> menggunakan E-Wallet atau M-Banking apa pun
                    </p>
                    
                    <!-- QR Box -->
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-inner border border-gray-200 dark:border-gray-700 mb-4">
                        @php
                            $qrString = !empty($paymentData['qr_string']) ? $paymentData['qr_string'] : 'https://totapstore.com/topup/checkout/' . $transaction->id;
                            $qrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qrString);
                        @endphp
                        <img src="{{ $qrSrc }}" alt="QRIS Code" class="w-52 h-52 mx-auto rounded-lg">
                        <div class="mt-2 text-[11px] font-bold text-gray-600">
                            <span>NMID: ID102003892719</span>
                        </div>
                    </div>

                    <!-- Supported E-Wallets -->
                    <div class="flex flex-wrap items-center justify-center gap-2 mb-6">
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">GoPay</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">OVO</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">DANA</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">ShopeePay</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">BCA Mobile</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">Livin'</span>
                        <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 rounded-lg">BRImo</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 max-w-md mx-auto">
                        <!-- WhatsApp Direct Button -->
                        <a href="{{ $waUrl }}" target="_blank" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 px-5 rounded-2xl shadow-lg shadow-emerald-600/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2.5 text-sm sm:text-base">
                            <i class="fab fa-whatsapp text-xl"></i>
                            <span>Konfirmasi / Kirim Bukti ke WhatsApp</span>
                        </a>

                        <!-- Verify / I Have Paid Button -->
                        <button id="verify-button" onclick="checkStatusManual()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3.5 px-5 rounded-2xl shadow-lg shadow-indigo-600/30 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2 text-sm sm:text-base cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>Saya Sudah Bayar (Cek Status)</span>
                        </button>
                    </div>

                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-4 max-w-md mx-auto">
                        💡 <em>Halaman ini otomatis mengecek status setiap 3 detik. Begitu pesanan Anda di-ACC oleh Admin, proses top up langsung selesai secara otomatis!</em>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Rating & Feedback Modal for Topup --}}
    <div id="ratingModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 max-w-md w-full text-center space-y-5 shadow-2xl border border-gray-100 dark:border-gray-700 animate-in fade-in zoom-in duration-200">
            <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-500 flex items-center justify-center text-3xl mx-auto shadow-inner">
                ✓
            </div>
            
            <div>
                <h3 class="text-2xl font-black text-gray-900 dark:text-white">Pesanan Berhasil di-ACC!</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Item top up / diamond telah sukses dikirimkan ke akun game Anda.</p>
            </div>

            <div class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-2xl border border-gray-200 dark:border-gray-600 text-left space-y-3">
                <div class="text-center">
                    <span class="text-xs font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Rating Layanan</span>
                    <h5 class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">Bagaimana Pengalaman Top Up Anda?</h5>
                </div>

                <form id="topupReviewForm" onsubmit="submitTopupReview(event)" class="space-y-3">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $transaction->id }}">
                    <input type="hidden" name="order_type" value="topup">
                    <input type="hidden" name="customer_name" value="{{ $transaction->target_field_1 ?? 'Gamer ToTap' }}">
                    <input type="hidden" name="customer_contact" value="{{ $transaction->target_field_1 ?? '' }}">
                    <input type="hidden" name="product_name" value="{{ $transaction->game->name ?? 'Top Up Game' }}">
                    <input type="hidden" id="topup-selected-rating" name="rating" value="5">

                    {{-- Star Picker --}}
                    <div class="flex flex-col items-center justify-center gap-1">
                        <div class="flex items-center gap-2 text-3xl cursor-pointer" id="topup-star-container">
                            <span class="star text-amber-400 transition transform hover:scale-125" onclick="setTopupRating(1)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125" onclick="setTopupRating(2)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125" onclick="setTopupRating(3)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125" onclick="setTopupRating(4)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125" onclick="setTopupRating(5)">★</span>
                        </div>
                        <span id="topup-rating-label" class="text-xs font-bold text-amber-500 mt-1">5/5 - Sangat Cepat & Puas! ⭐</span>
                    </div>

                    <div>
                        <textarea name="review_text" rows="2" placeholder="Tuliskan saran atau ulasan Anda..." class="w-full text-xs p-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 resize-none focus:ring-indigo-500"></textarea>
                    </div>

                    <button type="submit" id="topup-review-btn" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition cursor-pointer">
                        Kirim Ulasan & Selesai ⭐
                    </button>
                </form>

                <div id="topup-review-success" class="hidden text-center py-2">
                    <p class="text-xs text-emerald-600 font-bold">✓ Terima kasih! Ulasan Anda telah disimpan.</p>
                </div>
            </div>

            <a href="/" class="block text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 font-semibold">
                Kembali ke Beranda Utama →
            </a>
        </div>
    </div>
    
    <script>
        // Pengecekan Otomatis (Polling Real-Time setiap 3 Detik)
        let isPaid = false;
        const checkStatus = () => {
            if(isPaid) return;
            fetch('{{ route("topup.checkout.verify", $transaction->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    isPaid = true;
                    const btn = document.getElementById('verify-button');
                    if (btn) {
                        btn.innerHTML = '✓ Pembayaran Berhasil & Terkirim!';
                        btn.classList.replace('bg-indigo-600', 'bg-emerald-600');
                    }
                    const badge = document.getElementById('status-badge');
                    if (badge) {
                        badge.className = 'px-3.5 py-1.5 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5';
                        badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sukses Terkirim';
                    }
                    setTimeout(() => {
                        const modal = document.getElementById('ratingModal');
                        if (modal) modal.classList.remove('hidden');
                    }, 600);
                }
            })
            .catch(() => {});
        };
        
        setInterval(checkStatus, 3000);
        window.addEventListener('focus', checkStatus);

        function checkStatusManual() {
            const btn = document.getElementById('verify-button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1.5"></i> Mengecek Pembayaran...';
            btn.disabled = true;

            fetch('{{ route("topup.checkout.verify", $transaction->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if(data.success) {
                    isPaid = true;
                    btn.innerHTML = '✓ Pembayaran Berhasil!';
                    btn.classList.replace('bg-indigo-600', 'bg-emerald-600');
                    const modal = document.getElementById('ratingModal');
                    if (modal) modal.classList.remove('hidden');
                } else {
                    btn.innerHTML = originalText;
                    alert('Pembayaran Anda saat ini masih dalam antrean verifikasi Admin. Mohon kirim bukti transfer ke WhatsApp agar segera di-ACC!');
                }
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        // Rating Star Picker Logic
        function setTopupRating(val) {
            document.getElementById('topup-selected-rating').value = val;
            const stars = document.querySelectorAll('#topup-star-container .star');
            const labels = {
                1: '1/5 - Kurang Memuaskan 🙁',
                2: '2/5 - Cukup 🙂',
                3: '3/5 - Bagus & Standar 👍',
                4: '4/5 - Cepat & Mantap! ⚡',
                5: '5/5 - Sangat Cepat & Puas! ⭐'
            };
            stars.forEach((star, idx) => {
                if (idx < val) {
                    star.className = 'star text-amber-400 transition transform hover:scale-125';
                } else {
                    star.className = 'star text-gray-300 dark:text-gray-600 transition transform hover:scale-125';
                }
            });
            document.getElementById('topup-rating-label').innerText = labels[val] || '';
        }

        function submitTopupReview(e) {
            e.preventDefault();
            const form = document.getElementById('topupReviewForm');
            const formData = new FormData(form);
            const btn = document.getElementById('topup-review-btn');
            btn.disabled = true;
            btn.innerText = 'Menyimpan...';

            fetch('/api/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                form.classList.add('hidden');
                document.getElementById('topup-review-success').classList.remove('hidden');
                setTimeout(() => {
                    window.location.href = '/';
                }, 2000);
            })
            .catch(() => {
                btn.disabled = false;
                btn.innerText = 'Kirim Ulasan & Selesai ⭐';
                window.location.href = '/';
            });
        }
    </script>
</x-app-layout>
