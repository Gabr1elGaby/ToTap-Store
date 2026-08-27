<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 sm:p-10 border-b border-gray-200 dark:border-gray-700">
                @php
                    $isBalance = ($transaction->payment_method === 'balance');
                    $isSuccess = in_array(strtolower($transaction->status), ['paid', 'success', 'completed']);
                    $isProcessing = in_array(strtolower($transaction->status), ['processing', 'waiting']);
                    $isPaid = $isSuccess || $isProcessing || $isBalance;
                @endphp

                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Detail Pembayaran & Invoice</h2>
                        <p class="text-xs text-gray-600 dark:text-gray-300 font-medium mt-0.5">Selesaikan pembayaran untuk memproses pesanan top up game Anda.</p>
                    </div>
                    <div>
                        @if($isSuccess)
                            <span id="status-badge" class="px-3.5 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Sukses Terkirim
                            </span>
                        @elseif($isProcessing || $isBalance)
                            <span id="status-badge" class="px-3.5 py-1.5 bg-blue-100 dark:bg-blue-950 text-blue-800 dark:text-blue-300 border border-blue-300 dark:border-blue-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span>
                                Sedang Diproses Provider
                            </span>
                        @else
                            <span id="status-badge" class="px-3.5 py-1.5 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Order Summary Card -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 mb-8 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                        @if($transaction->game && $transaction->game->thumbnail)
                        <img src="{{ $transaction->game->thumbnail }}" class="w-16 h-16 rounded-2xl object-cover shadow-md border border-gray-200 dark:border-gray-700">
                        @endif
                        <div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $transaction->game->name ?? 'Top Up Game' }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-300 font-black text-sm">{{ $transaction->gameProduct->name ?? 'Nominal Diamond' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between sm:block bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-300 block font-bold">Nomor Invoice</span>
                            <span class="font-mono text-indigo-600 dark:text-indigo-300 font-black text-sm">{{ $transaction->invoice_number ?? ('TRX-' . $transaction->id) }}</span>
                        </div>
                        <div class="flex justify-between sm:block bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-300 block font-bold">{{ $transaction->game ? ($transaction->game->target_field_1 ?: 'Player ID') : 'Target ID' }}</span>
                            <span class="text-gray-900 dark:text-white font-black text-sm font-mono">
                                {{ $transaction->target_field_1 }}
                                @if($transaction->target_field_2)
                                <span class="text-indigo-600 dark:text-indigo-300">({{ $transaction->target_field_2 }})</span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Total Amount Banner -->
                <div class="flex justify-between items-center bg-indigo-50 dark:bg-gray-900 p-5 rounded-2xl border border-indigo-200 dark:border-gray-700 mb-8">
                    <div>
                        <span class="text-xs font-black text-indigo-600 dark:text-indigo-300 uppercase tracking-wider block">Metode: {{ $isBalance ? 'Saldo Akun' : 'QRIS All Payment' }}</span>
                        <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">Bebas biaya admin gateway (Rp0)</span>
                    </div>
                    <span class="text-3xl font-black text-indigo-600 dark:text-emerald-400 font-mono">Rp{{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $paymentData = json_decode($transaction->snap_token, true);
                    $adminWa = '6285198503253';
                    $waMsg = "Halo Admin ToTap Store, saya sudah transfer untuk pesanan Top Up Game:\n\n"
                           . "• Order ID: " . $transaction->id . "\n"
                           . "• Game: " . ($transaction->game->name ?? 'Game') . "\n"
                           . "• ID Akun: " . $transaction->target_field_1 . ($transaction->target_field_2 ? " (" . $transaction->target_field_2 . ")" : "") . "\n"
                           . "• Item: " . ($transaction->gameProduct->name ?? '-') . "\n"
                           . "• Total: Rp " . number_format($transaction->amount, 0, ',', '.') . "\n\n"
                           . "Mohon segera di-ACC dan diproses ya min, berikut bukti transfernya. Terima kasih!";
                    $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($waMsg);
                @endphp
                
                @php
                    $isAppService = $transaction->game && (str_contains(strtolower($transaction->game->category ?? ''), 'app') || str_contains(strtolower($transaction->game->category ?? ''), 'aplikasi') || str_contains(strtolower($transaction->game->category ?? ''), 'streaming'));
                @endphp
                <!-- 1. PAID SUCCESS / PROCESSING CARD (Shows directly if paid with balance or completed) -->
                <div id="topup-paid-card" class="{{ $isPaid ? '' : 'hidden' }} bg-white dark:bg-gray-900 rounded-3xl p-6 sm:p-8 mb-6 text-center border-2 {{ $isSuccess ? 'border-emerald-500/30 dark:border-emerald-500/20' : 'border-blue-500/30 dark:border-blue-500/20' }} shadow-lg space-y-6">
                    @if($isSuccess)
                        <div class="w-16 h-16 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-3xl mx-auto shadow-inner font-bold">
                            ✓
                        </div>

                        <div>
                            <span class="px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 font-bold rounded-full text-xs uppercase tracking-wider">
                                Transaksi Lunas & Berhasil
                            </span>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-3">
                                {{ $isAppService ? 'Aplikasi Premium Berhasil Diaktifkan!' : 'Top Up Game Berhasil!' }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
                                @if($isAppService)
                                    Undangan / akses akun premium telah dikirimkan ke Email: <strong>{{ $transaction->target_field_1 }}</strong>.
                                @else
                                    Item top up game Anda telah berhasil masuk ke akun <strong>{{ $transaction->target_field_1 }}</strong>.
                                @endif
                            </p>
                        </div>

                                            @if(!empty($transaction->provider_sn))
                        <!-- Kotak Informasi Akun & Password Resmi dari Provider -->
                        <div class="bg-amber-500/10 dark:bg-amber-950/40 border-2 border-amber-500 p-5 sm:p-6 rounded-2xl text-left space-y-3.5 max-w-lg mx-auto shadow-lg">
                            <div class="flex items-center justify-between border-b border-amber-500/30 pb-3">
                                <div class="flex items-center gap-2 font-black text-amber-950 dark:text-amber-300 text-sm sm:text-base">
                                    <i class="fas fa-key text-amber-500 text-base"></i> Detail Akun / Akses Langganan
                                </div>
                                <span class="text-xs font-black bg-amber-500 text-white dark:text-slate-900 px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                                    Aktif & Resmi
                                </span>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-xs font-black uppercase tracking-wider text-slate-800 dark:text-amber-200">
                                    Informasi Login / Serial Number:
                                </label>
                                <div class="p-3.5 bg-slate-900 dark:bg-slate-950 rounded-xl border-2 border-amber-500/40 font-mono text-xs font-bold text-amber-300 break-all select-all flex items-center justify-between gap-3 shadow-inner">
                                    <span class="leading-relaxed">{{ $transaction->provider_sn }}</span>
                                    <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($transaction->provider_sn) }}'); alert('Detail akun berhasil disalin ke clipboard!');" class="px-3.5 py-2 text-xs font-black text-white bg-amber-600 hover:bg-amber-700 active:scale-95 rounded-lg shadow-md transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 shrink-0">
                                        <i class="fas fa-copy"></i> Salin
                                    </button>
                                </div>
                            </div>

                            <div class="text-xs font-medium text-slate-800 dark:text-slate-200 space-y-1.5 pt-2.5 border-t border-amber-500/30">
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i>
                                    <span>Gunakan email dan password di atas untuk login ke aplikasi.</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <i class="fas fa-info-circle text-blue-600 dark:text-blue-400"></i>
                                    <span>Jika tertera link (URL), Anda dapat membuka link tersebut untuk melihat panduan / PIN profil Anda.</span>
                                </p>
                            </div>
                        </div>
                        @elseif($isAppService)
                        <!-- Kotak Informasi Email & Undangan Aplikasi Premium -->
                        <div class="bg-amber-500/10 dark:bg-amber-950/40 border-2 border-amber-500/60 p-5 rounded-2xl text-left space-y-2.5 max-w-lg mx-auto shadow-md">
                            <div class="flex items-center gap-2 font-black text-amber-950 dark:text-amber-300 text-sm">
                                <i class="fas fa-envelope-open-text text-base text-amber-500"></i> Pengiriman Undangan / Akun ke Email
                            </div>
                            <p class="text-xs text-slate-800 dark:text-slate-200 leading-relaxed font-medium">
                                Akses / link invite untuk <strong>{{ $transaction->gameProduct->name ?? 'Aplikasi Premium' }}</strong> dikirim ke: <br>
                                <span class="inline-block mt-1 font-mono font-bold text-amber-900 dark:text-amber-200 bg-amber-200/60 dark:bg-amber-900/60 px-3 py-1 rounded-lg border border-amber-400 dark:border-amber-700">📧 {{ $transaction->target_field_1 }}</span>
                            </p>
                            <div class="text-xs text-slate-700 dark:text-slate-300 space-y-1.5 pt-2.5 border-t border-amber-500/30">
                                <p class="flex items-center gap-1.5"><i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i> Buka email Anda (Inbox Gmail, Outlook, atau Yahoo).</p>
                                <p class="flex items-center gap-1.5"><i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400"></i> Buka pesan undangan masuk dari provider dan klik <strong>Terima Undangan (Accept)</strong>.</p>
                                <p class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400 pt-0.5 text-[11px]">💡 <em>Jika belum masuk, periksa juga folder <strong>Spam / Promosi / Update</strong> email Anda.</em></p>
                            </div>
                        @endif
                    @else
                        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center text-3xl mx-auto shadow-inner font-bold animate-pulse">
                            <i class="fas fa-spinner fa-spin text-2xl"></i>
                        </div>

                        <div>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-bold rounded-full text-xs uppercase tracking-wider">
                                Pembayaran Lunas via {{ $isBalance ? 'Saldo Akun' : 'QRIS' }}
                            </span>
                            <h3 class="text-2xl font-black text-gray-900 dark:text-white mt-3">Pesanan Sedang Diproses!</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-md mx-auto">
                                Pembayaran sebesar <strong>Rp{{ number_format($transaction->amount, 0, ',', '.') }}</strong> telah diterima. Pesanan Anda saat ini sedang dalam antrean pengiriman server provider ke email/akun Anda.
                            </p>
                        </div>
                    @endif

                    <!-- Top Up Rating & Kritik/Saran Form -->
                    <div class="bg-slate-50 dark:bg-gray-800/80 p-5 sm:p-6 rounded-2xl border border-gray-200 dark:border-gray-700 text-left space-y-4 max-w-lg mx-auto shadow-sm">
                        <div class="text-center">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 dark:text-indigo-400">Kepuasan Pelanggan</span>
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">Beri Rating & Masukan untuk Super Admin</h4>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-0.5">Kritik & saran hanya dapat dibaca secara privat oleh Super Admin untuk terus meningkatkan kecepatan & kualitas layanan.</p>
                        </div>

                        <form id="topupInlineReviewForm" onsubmit="submitTopupInlineReview(event)" class="space-y-3">
                            <input type="hidden" name="order_id" value="{{ $transaction->invoice_number ?? ('TRX-' . $transaction->id) }}">
                            <input type="hidden" name="order_type" value="topup">
                            <input type="hidden" name="customer_name" value="{{ $transaction->target_field_1 ?? 'Gamer ToTap' }}">
                            <input type="hidden" name="customer_contact" value="{{ $transaction->target_field_1 ?? '' }}">
                            <input type="hidden" name="product_name" value="{{ $transaction->game ? $transaction->game->name : 'Top Up' }} ({{ $transaction->gameProduct ? $transaction->gameProduct->name : 'Diamond' }})">
                            <input type="hidden" id="topup-inline-selected-rating" name="rating" value="5">

                            <!-- Star Picker -->
                            <div class="flex flex-col items-center justify-center gap-1">
                                <div class="flex items-center gap-2 text-3xl cursor-pointer" id="topup-inline-star-container">
                                    <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setTopupInlineRating(1)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setTopupInlineRating(2)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setTopupInlineRating(3)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setTopupInlineRating(4)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setTopupInlineRating(5)">★</span>
                                </div>
                                <span id="topup-inline-rating-label" class="text-xs font-bold text-amber-500 mt-1">5/5 - Sangat Cepat & Puas! ⭐</span>
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kritik & Saran Khusus Super Admin:</label>
                                <textarea name="review_text" rows="2" placeholder="Tuliskan pengalaman top up, kritik, atau saran game baru..." class="w-full text-xs p-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 resize-none focus:ring-indigo-500"></textarea>
                            </div>

                            <button type="submit" id="topup-inline-review-btn" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 text-white font-bold rounded-xl text-xs shadow-md transition cursor-pointer">
                                Kirim Rating & Masukan ⭐
                            </button>
                        </form>

                        <div id="topup-inline-review-success" class="hidden text-center py-3 space-y-1">
                            <div class="w-7 h-7 bg-emerald-100 dark:bg-emerald-950 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-sm font-bold">
                                ✓
                            </div>
                            <h5 class="text-xs font-bold text-gray-900 dark:text-white">Terima Kasih Atas Ulasan Anda!</h5>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400">Rating & saran Anda telah berhasil diteruskan ke Super Admin.</p>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="pt-2 flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('transactions.invoice', $transaction->id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <span>Lihat Invoice Resmi</span>
                        </a>
                        <a href="/" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-xl transition inline-flex items-center justify-center gap-1.5">
                            <span>Kembali ke Beranda</span>
                        </a>
                    </div>
                </div>

                <!-- 2. PENDING QRIS & PAYMENT CARD (Hidden if already paid or paid with balance) -->
                <div id="topup-pending-card" class="{{ $isPaid ? 'hidden' : '' }} bg-gray-50 dark:bg-gray-900 rounded-3xl p-6 sm:p-8 mb-6 text-center border-2 border-gray-200 dark:border-gray-700 shadow-lg">
                    <!-- QRIS Header -->
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 inline-block">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-7 object-contain">
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-6">
                        Scan QRIS di bawah menggunakan M-Banking atau E-Wallet apa pun. <br>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold rounded-full mt-2 text-xs border border-emerald-300 dark:border-emerald-700">
                            ⚡ Nominal Terisi Otomatis: Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                        </span>
                    </p>
                    
                    <!-- QR Box -->
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-md border-2 border-gray-200 dark:border-gray-600 mb-4">
                        @php
                            $dynamicQris = \App\Helpers\QrisHelper::getDynamicQrisForAmount((int) $transaction->amount);
                            $qrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($dynamicQris);
                        @endphp
                        <img src="{{ $qrSrc }}" alt="QRIS Dinamis ToTap Store" class="w-56 h-56 mx-auto rounded-lg">
                        <div class="mt-3 text-center space-y-0.5">
                            <span class="text-xs font-black text-gray-900 block">TOTAP STORE, GAMING</span>
                            <span class="text-[11px] font-mono text-gray-600 block">NMID: ID1026577601523</span>
                        </div>
                    </div>

                    <!-- Supported E-Wallets -->
                    <div class="flex flex-wrap items-center justify-center gap-2 mb-6">
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">GoPay</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">OVO</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">DANA</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">ShopeePay</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">BCA Mobile</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">Livin'</span>
                        <span class="px-2.5 py-1 bg-gray-200 dark:bg-gray-800 text-xs font-bold text-gray-700 dark:text-gray-300 rounded-lg">BRImo</span>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-3 max-w-md mx-auto">
                        <!-- WhatsApp Direct Button -->
                        <a href="{{ $waUrl }}" target="_blank" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff !important;" class="w-full font-bold py-4 px-5 rounded-2xl shadow-xl shadow-emerald-600/30 hover:opacity-95 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-3 text-sm sm:text-base cursor-pointer">
                            <svg class="w-6 h-6 fill-current text-white flex-shrink-0" viewBox="0 0 24 24">
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.53 1.771.815 2.791.815 3.181 0 5.767-2.586 5.768-5.766 0-3.18-2.586-5.768-5.768-5.768zm3.393 8.307c-.149.421-.849.771-1.189.815-.34.043-.77.065-2.221-.525-1.745-.71-2.87-2.478-2.958-2.593-.088-.115-.711-.944-.711-1.802 0-.858.451-1.282.611-1.455.16-.173.349-.216.465-.216.116 0 .233.001.334.007.108.005.249-.041.389.296.149.36.508 1.238.552 1.328.044.09.073.195.015.311-.058.116-.088.188-.175.289-.088.101-.184.225-.264.303-.088.086-.179.179-.077.354.102.175.451.744.969 1.205.666.594 1.228.777 1.403.864.175.086.277.073.379-.044.102-.116.437-.508.553-.682.116-.175.233-.146.393-.088.16.058 1.018.48 1.193.567.175.088.291.131.334.204.044.073.044.423-.105.844z"/>
                            </svg>
                            <span class="font-black text-white tracking-wide">Konfirmasi / Kirim Bukti ke WhatsApp</span>
                        </a>

                        <!-- Verify / I Have Paid Button -->
                        <button id="verify-button" onclick="checkStatusManual()" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff !important;" class="w-full font-bold py-3.5 px-5 rounded-2xl shadow-xl shadow-indigo-600/30 hover:opacity-95 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2.5 text-sm sm:text-base cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="font-extrabold text-white">Saya Sudah Bayar (Cek Status)</span>
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
                <i class="fas fa-check text-2xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white">Rating Berhasil Dikirim!</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Terima kasih atas ulasan dan masukan berharga Anda untuk ToTap Store.</p>
            </div>
            <button onclick="document.getElementById('ratingModal').classList.add('hidden')" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition cursor-pointer">
                Tutup
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let isAlreadyPaid = {{ $isPaid ? 'true' : 'false' }};
        let pollInterval = null;

        function checkStatusManual() {
            const btn = document.getElementById('verify-button');
            if (btn) {
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memeriksa Status...';
                btn.disabled = true;
            }

            fetch('{{ route("topup.checkout.verify", $transaction->id) }}', {
                headers: { 'Accept': 'application/json' }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    onPaymentSuccess();
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Belum Terkonfirmasi',
                        text: data.message || 'Pembayaran belum terdeteksi. Jika sudah transfer via QRIS, silakan kirim bukti ke WhatsApp Admin untuk proses kilat.',
                        confirmButtonColor: '#4f46e5'
                    });
                }
            })
            .catch(() => {})
            .finally(() => {
                if (btn) {
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg><span class="font-extrabold text-white">Saya Sudah Bayar (Cek Status)</span>';
                    btn.disabled = false;
                }
            });
        }

        function onPaymentSuccess() {
            isAlreadyPaid = true;
            if (pollInterval) clearInterval(pollInterval);

            const pendingCard = document.getElementById('topup-pending-card');
            const paidCard = document.getElementById('topup-paid-card');
            const badge = document.getElementById('status-badge');

            if (pendingCard) pendingCard.classList.add('hidden');
            if (paidCard) paidCard.classList.remove('hidden');
            if (badge) {
                badge.className = 'px-3.5 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5';
                badge.innerHTML = '<span class="w-2 h-2 rounded-full bg-emerald-500"></span> Sukses Terkirim';
            }
        }

        if (!isAlreadyPaid) {
            pollInterval = setInterval(() => {
                fetch('{{ route("topup.checkout.verify", $transaction->id) }}', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        onPaymentSuccess();
                    }
                })
                .catch(() => {});
            }, 3000);
        }

        function setTopupInlineRating(val) {
            document.getElementById('topup-inline-selected-rating').value = val;
            const stars = document.querySelectorAll('#topup-inline-star-container .star');
            const labels = {
                1: '1/5 - Sangat Kurang 😞',
                2: '2/5 - Kurang Memuaskan 😐',
                3: '3/5 - Cukup Bagus 🙂',
                4: '4/5 - Bagus & Cepat 😊',
                5: '5/5 - Sangat Cepat & Puas! ⭐'
            };
            stars.forEach((s, idx) => {
                s.className = (idx < val) ? 'star text-amber-400 transition transform hover:scale-125 select-none' : 'star text-gray-300 dark:text-gray-600 transition transform hover:scale-125 select-none';
            });
            document.getElementById('topup-inline-rating-label').innerText = labels[val] || '';
        }

        function submitTopupInlineReview(e) {
            e.preventDefault();
            const btn = document.getElementById('topup-inline-review-btn');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim Ulasan...';
            btn.disabled = true;

            const formData = new FormData(document.getElementById('topupInlineReviewForm'));
            fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById('topupInlineReviewForm').classList.add('hidden');
                document.getElementById('topup-inline-review-success').classList.remove('hidden');
            })
            .catch(() => {
                document.getElementById('topupInlineReviewForm').classList.add('hidden');
                document.getElementById('topup-inline-review-success').classList.remove('hidden');
            });
        }
    </script>
</x-app-layout>
