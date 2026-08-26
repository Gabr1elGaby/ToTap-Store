<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-black text-2xl text-gray-900 dark:text-white leading-tight flex items-center gap-2.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-400 text-gray-950 flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0">
                    <svg class="w-6 h-6 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                </div>
                <span>Pembayaran Isi Saldo</span>
            </h2>
            <a href="{{ route('deposit.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold text-xs rounded-xl transition shadow-sm border border-gray-200 dark:border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Isi Saldo
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-xl mx-auto px-4 sm:px-6">

            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 shadow-2xl space-y-6 text-center">

                @if($deposit->status === 'success' || $deposit->status === 'paid')
                    <!-- TAMPILAN SUDAH DIBAYAR -->
                    <div class="w-20 h-20 mx-auto rounded-full bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border-4 border-emerald-500/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-2xl font-black text-gray-900 dark:text-white">Isi Saldo Berhasil!</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Saldo akun Anda telah bertambah otomatis.</p>
                    </div>

                    <div class="p-5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-700 text-center">
                        <div class="text-xs text-emerald-800 dark:text-emerald-300 font-bold">Total Saldo Masuk</div>
                        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 mt-1 font-mono">
                            Rp{{ number_format($deposit->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="pt-2">
                        <a href="{{ route('topup.index') }}" class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-2xl shadow-lg transition flex items-center justify-center gap-2">
                            <span>Mulai Belanja Top Up Game</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                @else
                    <!-- TAMPILAN MENUNGGU PEMBAYARAN QRIS -->
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950 text-amber-800 dark:text-amber-300 text-xs font-black border border-amber-300 dark:border-amber-700 mb-2">
                            <span class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
                            Menunggu Pembayaran
                        </div>
                        <h3 class="text-xl font-black text-gray-900 dark:text-white">Scan QRIS untuk Isi Saldo</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5">No. Deposit: <span class="font-mono font-bold text-indigo-600 dark:text-indigo-400">{{ $deposit->id }}</span></p>
                    </div>

                    <!-- Nominal Tagihan -->
                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700">
                        <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold">Nominal Saldo yang Masuk:</div>
                        <div class="text-3xl font-black text-emerald-600 dark:text-emerald-400 font-mono tracking-tight mt-0.5">
                            Rp{{ number_format($deposit->amount, 0, ',', '.') }}
                        </div>
                    </div>

                    <!-- Kotak QR Code (Selalu Jelas & Kontras) -->
                    <div class="bg-white p-6 rounded-3xl border-2 border-dashed border-gray-300 shadow-md inline-block max-w-full text-gray-900">
                        <div class="flex items-center justify-between mb-3 px-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain">
                            <span class="text-[11px] font-black text-gray-600 uppercase tracking-wider">NMID: ID1026577601523</span>
                        </div>
                        
                        @php
                            $qrImgUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&margin=10&data=' . urlencode($qrisString);
                        @endphp
                        <img src="{{ $qrImgUrl }}" alt="QRIS Deposit" class="w-64 h-64 mx-auto rounded-xl object-contain border border-gray-100 shadow-inner bg-white">

                        <div class="text-center mt-3 text-gray-900">
                            <div class="text-xs font-black text-gray-900">ToTap Store, Gaming</div>
                            <div class="text-[11px] text-gray-600 font-semibold">QRIS Dinamis • Nominal Pas Otomatis</div>
                        </div>
                    </div>

                    <!-- Petunjuk Singkat -->
                    <div class="text-left text-xs bg-indigo-50/70 dark:bg-indigo-950/60 p-4.5 rounded-2xl border border-indigo-200 dark:border-indigo-800 text-indigo-950 dark:text-indigo-100 space-y-2 font-medium">
                        <div class="font-bold flex items-center gap-1.5 text-indigo-700 dark:text-indigo-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Panduan Pembayaran:
                        </div>
                        <div>1. Buka aplikasi e-Wallet atau M-Banking favorit Anda.</div>
                        <div>2. Pilih menu <strong>Scan QR / Bayar</strong> lalu arahkan kamera ke kode QRIS di atas.</div>
                        <div>3. Nominal akan otomatis terisi <strong>Rp{{ number_format($deposit->amount, 0, ',', '.') }}</strong>, lalu selesaikan pembayaran.</div>
                    </div>

                    <!-- Tombol Cek Status & WhatsApp -->
                    <div class="space-y-3 pt-2">
                        <button type="button" 
                                onclick="checkDepositStatus()" 
                                id="btn-check-status"
                                style="background-color: #10b981; color: #022c22;"
                                class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-black text-sm rounded-2xl shadow-lg transition flex items-center justify-center gap-2 cursor-pointer border border-emerald-400">
                            <svg class="w-4 h-4 animate-spin hidden text-gray-950" id="spinner-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span id="btn-check-text" class="text-gray-950 font-black">Saya Sudah Bayar (Cek Saldo)</span>
                        </button>

                        <a href="https://wa.me/6281234567890?text=Halo%20Admin%20ToTap%20Store,%20saya%20sudah%20melakukan%20isi%20saldo%20dengan%20nomor%20deposit:%20{{ $deposit->id }}%20sebesar%20Rp{{ number_format($deposit->amount, 0, ',', '.') }}" 
                           target="_blank" 
                           class="w-full py-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold text-xs rounded-2xl transition flex items-center justify-center gap-2 border border-gray-200 dark:border-gray-600">
                            <i class="fab fa-whatsapp text-emerald-500 text-sm"></i>
                            <span>Konfirmasi Manual via WhatsApp</span>
                        </a>
                    </div>
                @endif

            </div>

        </div>
    </div>

    @if($deposit->status === 'pending')
    <script>
        function checkDepositStatus() {
            const btn = document.getElementById('btn-check-status');
            const spinner = document.getElementById('spinner-icon');
            const text = document.getElementById('btn-check-text');

            if (spinner) spinner.classList.remove('hidden');
            if (text) text.innerText = 'Memeriksa Pembayaran...';

            fetch('{{ route('deposit.status.api', $deposit->id) }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.paid || data.status === 'success' || data.status === 'paid') {
                    window.location.reload();
                } else {
                    setTimeout(() => {
                        if (spinner) spinner.classList.add('hidden');
                        if (text) text.innerText = 'Saya Sudah Bayar (Cek Saldo)';
                        Swal.fire({
                            icon: 'info',
                            title: 'Belum Terdeteksi',
                            text: 'Pembayaran belum terdeteksi sistem. Jika sudah berhasil transfer, mohon tunggu 5-10 detik atau hubungi Admin.',
                            confirmButtonColor: '#4f46e5'
                        });
                    }, 800);
                }
            })
            .catch(e => {
                if (spinner) spinner.classList.add('hidden');
                if (text) text.innerText = 'Saya Sudah Bayar (Cek Saldo)';
            });
        }

        // Auto poll every 4 seconds
        setInterval(() => {
            fetch('{{ route('deposit.status.api', $deposit->id) }}', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.paid || data.status === 'success' || data.status === 'paid') {
                    window.location.reload();
                }
            })
            .catch(e => {});
        }, 4000);
    </script>
    @endif
</x-app-layout>
