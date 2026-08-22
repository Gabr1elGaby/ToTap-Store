<x-app-layout>
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 sm:p-10 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Pembayaran</h2>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-bold uppercase tracking-wider">Menunggu Pembayaran</span>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-900 rounded-xl p-6 mb-8">
                    <div class="flex items-center gap-4 mb-4 pb-4 border-b border-gray-200 dark:border-gray-800">
                        @if($transaction->game->thumbnail)
                        <img src="{{ $transaction->game->thumbnail }}" class="w-16 h-16 rounded-xl object-cover shadow">
                        @endif
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ $transaction->game->name }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $transaction->gameProduct->name }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">Order ID</span>
                            <span class="font-mono text-gray-900 dark:text-white font-medium">{{ $transaction->id }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">{{ $transaction->game->target_field_1 }}</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->target_field_1 }}</span>
                        </div>
                        @if($transaction->target_field_2)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500 dark:text-gray-400">{{ $transaction->game->target_field_2 }}</span>
                            <span class="text-gray-900 dark:text-white font-medium">{{ $transaction->target_field_2 }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-8">
                    <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Total Pembayaran</span>
                    <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                </div>
                
                @php
                    $paymentData = json_decode($transaction->snap_token, true);
                @endphp
                
                <div class="bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl p-8 mb-6 text-center border border-indigo-100 dark:border-indigo-800">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Selesaikan Pembayaran</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">Selesaikan pembayaran sebelum <span class="font-bold text-indigo-600 dark:text-indigo-400" id="countdown">24:00:00</span></p>
                    
                    @if($paymentData && $paymentData['type'] === 'qris')
                        <div class="inline-block bg-white p-4 rounded-xl shadow-sm mb-4">
                            <img src="{{ $paymentData['qr_url'] }}" alt="QRIS Code" class="w-48 h-48 mx-auto">
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Scan QR Code ini menggunakan GoPay, ShopeePay, OVO, DANA, atau M-Banking Anda.</p>
                    @elseif($paymentData && $paymentData['type'] === 'va')
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm mb-4 relative group">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Nomor Virtual Account ({{ $paymentData['bank'] }})</p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                <p id="va-text" class="text-xl sm:text-3xl font-mono font-bold text-gray-900 dark:text-white tracking-wider break-all" style="word-break: break-all;">{{ $paymentData['va_number'] }}</p>
                                
                                <button onclick="copyVA()" id="copy-btn" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-800 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-sm transition-all shadow-sm border border-indigo-100 dark:border-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <span id="copy-text">Salin</span>
                                </button>
                            </div>
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Transfer tepat sesuai nominal hingga 3 digit terakhir.</p>
                        
                        <!-- TUTORIAL / INSTRUKSI PEMBAYARAN -->
                        <div class="text-left bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mt-4 w-full">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                Cara Pembayaran
                            </h4>
                            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400 w-full whitespace-normal">
                                @if(isset($paymentData['bank']) && $paymentData['bank'] === 'BCA')
                                    <p><strong>1.</strong> Buka aplikasi <strong>BCA Mobile</strong> atau M-BCA.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>m-Transfer</strong> > <strong>BCA Virtual Account</strong>.</p>
                                @elseif(isset($paymentData['bank']) && $paymentData['bank'] === 'MANDIRI')
                                    <p><strong>1.</strong> Buka aplikasi <strong>Livin' by Mandiri</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Bayar</strong> > <strong>Multipayment / E-Commerce</strong>.</p>
                                @elseif(isset($paymentData['bank']) && $paymentData['bank'] === 'BNI')
                                    <p><strong>1.</strong> Buka aplikasi <strong>BNI Mobile Banking</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Transfer</strong> > <strong>Virtual Account Billing</strong>.</p>
                                @else
                                    <p><strong>1.</strong> Buka aplikasi Mobile Banking atau ATM <strong>{{ $paymentData['bank'] ?? 'Anda' }}</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Transfer > Virtual Account</strong> (Atau Pembayaran VA/Briva).</p>
                                @endif
                                <p><strong>3.</strong> Masukkan nomor Virtual Account di atas.</p>
                                <p><strong>4.</strong> Pastikan nominal dan nama sesuai dengan pesanan ToTap Store.</p>
                                <p><strong>5.</strong> Masukkan PIN Anda untuk menyelesaikan pembayaran.</p>
                            </div>
                        </div>
                    @else
                        <p class="text-red-500 font-bold">Data pembayaran tidak valid.</p>
                    @endif
                </div>
                
                <button id="verify-button" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 rounded-xl shadow-lg transition-all duration-300 flex justify-center items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    SAYA SUDAH MEMBAYAR
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Timer Mundur Sederhana (24 Jam)
        let timeLeft = 24 * 60 * 60;
        setInterval(() => {
            if(timeLeft <= 0) return;
            timeLeft--;
            const h = Math.floor(timeLeft / 3600).toString().padStart(2, '0');
            const m = Math.floor((timeLeft % 3600) / 60).toString().padStart(2, '0');
            const s = (timeLeft % 60).toString().padStart(2, '0');
            document.getElementById('countdown').innerText = `${h}:${m}:${s}`;
        }, 1000);
        
        // Pengecekan Otomatis (Polling) setiap 5 Detik
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
                    document.getElementById('verify-button').innerHTML = 'Pembayaran Berhasil! Mengalihkan...';
                    document.getElementById('verify-button').classList.replace('bg-indigo-600', 'bg-green-500');
                    setTimeout(() => window.location.href = '/', 2000);
                }
            });
        };
        setInterval(checkStatus, 5000); // Polling otomatis

        document.getElementById('verify-button').onclick = function(){
            this.innerHTML = 'Mengecek Pembayaran...';
            checkStatus();
        };

        // Fungsi Salin VA
        function copyVA() {
            const vaNumber = document.getElementById('va-text').innerText;
            navigator.clipboard.writeText(vaNumber).then(() => {
                const copyBtn = document.getElementById('copy-btn');
                const copyText = document.getElementById('copy-text');
                
                // Ubah tampilan sesaat
                copyBtn.classList.replace('bg-indigo-50', 'bg-green-100');
                copyBtn.classList.replace('text-indigo-600', 'text-green-700');
                copyText.innerText = 'Tersalin!';
                
                setTimeout(() => {
                    copyBtn.classList.replace('bg-green-100', 'bg-indigo-50');
                    copyBtn.classList.replace('text-green-700', 'text-indigo-600');
                    copyText.innerText = 'Salin';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin: ' + err);
            });
        }
    </script>
</x-app-layout>
