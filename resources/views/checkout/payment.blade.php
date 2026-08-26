<x-app-layout>
    <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <div class="p-6 sm:p-10 border-b border-gray-200 dark:border-gray-700 text-center">
                
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="text-left">
                        <h2 class="text-2xl font-black text-gray-900 dark:text-white">Detail Pembayaran & Invoice</h2>
                        <p class="text-xs text-gray-600 dark:text-gray-300 font-medium mt-0.5">Selesaikan pembayaran untuk mengaktifkan lisensi {{ $order->product->name ?? 'Software' }}.</p>
                    </div>
                    <div>
                        @if($order->payment_status === 'PAID')
                            <span class="px-3.5 py-1.5 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                Lunas / Aktif
                            </span>
                        @else
                            <span class="px-3.5 py-1.5 bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 border border-amber-300 dark:border-amber-700 rounded-full text-xs font-bold uppercase tracking-wider inline-flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                Menunggu Pembayaran
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Order Summary Card -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-2xl p-6 mb-8 border border-gray-200 dark:border-gray-700 text-left">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl shadow-inner font-black">
                            <i class="fas fa-desktop"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ $order->product->name ?? 'Sistem Kasir (POS)' }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-300 font-black text-sm">Paket: {{ $order->plan->name ?? 'Tahunan' }} ({{ $order->plan->duration_days ?? 365 }} Hari)</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-300 block font-bold">Nomor Invoice</span>
                            <span class="font-mono text-indigo-600 dark:text-indigo-300 font-black text-sm">{{ $order->order_number }}</span>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-300 block font-bold">Akun Pemesan</span>
                            <span class="text-gray-900 dark:text-white font-black text-sm font-mono">{{ $order->user->email ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Total Amount Banner -->
                <div class="flex justify-between items-center bg-indigo-50 dark:bg-gray-900 p-5 rounded-2xl border border-indigo-200 dark:border-gray-700 mb-8">
                    <div class="text-left">
                        <span class="text-xs font-black text-indigo-600 dark:text-indigo-300 uppercase tracking-wider block">Metode: QRIS All Payment</span>
                        <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">Bebas biaya admin gateway (Rp0)</span>
                    </div>
                    <span class="text-3xl font-black text-indigo-600 dark:text-emerald-400 font-mono">Rp{{ number_format($order->amount, 0, ',', '.') }}</span>
                </div>

                @php
                    $adminWa = '6285198503253';
                    $waMsg = "Halo Admin ToTap Store, saya sudah melakukan transfer pembayaran untuk pesanan:\n\n"
                           . "• No. Invoice: " . $order->order_number . "\n"
                           . "• Layanan: " . ($order->product->name ?? 'Sistem Kasir (POS)') . " (" . ($order->plan->name ?? 'Tahunan') . ")\n"
                           . "• Akun: " . ($order->user->email ?? '-') . "\n"
                           . "• Total: Rp " . number_format($order->amount, 0, ',', '.') . "\n\n"
                           . "Berikut saya lampirkan bukti transfernya. Mohon segera di-ACC agar akses lisensi saya aktif. Terima kasih!";
                    $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($waMsg);
                @endphp

                <!-- QRIS Box Card -->
                <div class="bg-gray-50 dark:bg-gray-900 rounded-3xl p-6 sm:p-8 mb-6 text-center border-2 border-gray-200 dark:border-gray-700 shadow-lg">
                    <!-- QRIS Header -->
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <div class="bg-white p-2 rounded-xl shadow-sm border border-gray-200 inline-block">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-7 object-contain">
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-600 dark:text-gray-300 mb-6">
                        Scan QRIS di bawah menggunakan M-Banking atau E-Wallet apa pun. <br>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 font-bold rounded-full mt-2 text-xs border border-emerald-300 dark:border-emerald-700">
                            ⚡ Nominal Terisi Otomatis: Rp{{ number_format($order->amount, 0, ',', '.') }}
                        </span>
                    </p>
                    
                    <!-- QR Box -->
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-md border-2 border-gray-200 dark:border-gray-600 mb-4">
                        @php
                            $dynamicQris = \App\Helpers\QrisHelper::getDynamicQrisForAmount((int) $order->amount);
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
                                <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.711 2.598 2.669-.699c.969.53 1.771.815 2.791.815 3.181 0 5.767-2.586 5.768-5.768 0-3.18-2.586-5.768-5.768-5.768zm3.393 8.307c-.149.421-.849.771-1.189.815-.34.043-.77.065-2.221-.525-1.745-.71-2.87-2.478-2.958-2.593-.088-.115-.711-.944-.711-1.802 0-.858.451-1.282.611-1.455.16-.173.349-.216.465-.216.116 0 .233.001.334.007.108.005.249-.041.389.296.149.36.508 1.238.552 1.328.044.09.073.195.015.311-.058.116-.088.188-.175.289-.088.101-.184.225-.264.303-.088.086-.179.179-.077.354.102.175.451.744.969 1.205.666.594 1.228.777 1.403.864.175.086.277.073.379-.044.102-.116.437-.508.553-.682.116-.175.233-.146.393-.088.16.058 1.018.48 1.193.567.175.088.291.131.334.204.044.073.044.423-.105.844z"/>
                            </svg>
                            <span class="font-black text-white tracking-wide">Konfirmasi / Kirim Bukti ke WhatsApp</span>
                        </a>

                        <!-- I Have Paid Button -->
                        <form action="{{ route('payment.simulate', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); color: #ffffff !important;" class="w-full font-bold py-3.5 px-5 rounded-2xl shadow-xl shadow-indigo-600/30 hover:opacity-95 transition-all transform hover:-translate-y-0.5 flex justify-center items-center gap-2.5 text-sm sm:text-base cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="font-extrabold text-white">Saya Sudah Bayar (Konfirmasi)</span>
                            </button>
                        </form>
                    </div>

                    <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-4 max-w-md mx-auto">
                        💡 <em>Setelah transfer, kirim bukti ke WhatsApp Admin agar pesanan lisensi Anda langsung di-ACC dan diaktifkan.</em>
                    </p>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
