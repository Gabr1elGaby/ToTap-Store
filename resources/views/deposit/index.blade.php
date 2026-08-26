<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-black text-2xl text-gray-900 dark:text-white leading-tight flex items-center gap-2.5">
                    <div class="w-10 h-10 rounded-xl bg-emerald-400 text-gray-950 flex items-center justify-center shadow-lg shadow-emerald-500/20 shrink-0">
                        <svg class="w-6 h-6 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <span>Isi Saldo Akun (Deposit)</span>
                </h2>
                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Top up saldo dompet ToTap Store instan via QRIS untuk checkout game secepat kilat.</p>
            </div>
            <a href="{{ route('topup.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold text-xs rounded-xl transition shadow-sm border border-gray-200 dark:border-gray-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Top Up Game
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            @if(session('success'))
                <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-300 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200 font-bold text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/60 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-200 font-bold text-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- Sisi Kiri: Form Isi Saldo -->
                <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 shadow-xl" x-data="{ amount: 50000, customAmount: '' }">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                        <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-indigo-600/30">1</span>
                        Pilih Nominal Saldo
                    </h3>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mb-6">Pilih paket nominal siap pakai atau masukkan nominal sesuka Anda (Min. Rp5.000).</p>

                    <form action="{{ route('deposit.process') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Pilihan Preset Nominal -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            @foreach($presets as $p)
                                <button type="button" 
                                        @click="amount = {{ $p }}; customAmount = ''"
                                        :class="amount == {{ $p }} ? 'border-2 border-emerald-500 bg-emerald-50 dark:bg-emerald-950/60 ring-2 ring-emerald-500/20 text-emerald-700 dark:text-emerald-300 font-black' : 'border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-100 font-bold hover:border-gray-300 dark:hover:border-gray-600'"
                                        class="p-4 rounded-2xl text-center transition-all transform active:scale-95 shadow-sm cursor-pointer">
                                    <div class="text-xs text-gray-500 dark:text-gray-400 font-semibold mb-1">Paket</div>
                                    <div class="text-sm sm:text-base tracking-tight font-black" :class="amount == {{ $p }} ? 'text-emerald-600 dark:text-emerald-300 font-black' : 'text-gray-900 dark:text-white font-bold'">Rp{{ number_format($p, 0, ',', '.') }}</div>
                                </button>
                            @endforeach
                        </div>

                        <!-- Input Nominal Custom -->
                        <div>
                            <label class="block text-xs font-bold text-gray-800 dark:text-gray-200 mb-2">Atau Masukkan Nominal Lain (Rp)</label>
                            <div class="flex rounded-2xl shadow-sm overflow-hidden border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition">
                                <div class="px-5 py-3.5 bg-gray-100 dark:bg-gray-800 text-gray-800 dark:text-gray-200 font-mono font-black text-base flex items-center border-r border-gray-300 dark:border-gray-700 select-none">
                                    Rp
                                </div>
                                <input type="number" 
                                       name="amount" 
                                       x-model="amount" 
                                       min="5000" 
                                       max="5000000" 
                                       step="1000"
                                       required
                                       placeholder="50000"
                                       class="w-full px-4 py-3.5 bg-transparent border-0 font-mono font-black text-gray-900 dark:text-white text-base focus:ring-0 focus:outline-none">
                            </div>
                            <div class="text-[11px] text-gray-500 dark:text-gray-400 mt-1.5 flex justify-between font-semibold">
                                <span>Minimal Rp5.000</span>
                                <span>Maksimal Rp5.000.000</span>
                            </div>
                        </div>

                        <!-- Metode Pembayaran: QRIS Otomatis -->
                        <div class="pt-2">
                            <h3 class="text-lg font-black text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shadow-md shadow-indigo-600/30">2</span>
                                Metode Pembayaran
                            </h3>
                            <div class="p-4 rounded-2xl border-2 border-indigo-600 dark:border-indigo-500 bg-indigo-50/70 dark:bg-gray-900 ring-2 ring-indigo-500/20 flex items-center justify-between shadow-sm">
                                <div class="flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-bold text-sm text-gray-900 dark:text-white">QRIS All Payment</div>
                                        <div class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold">Proses Cepat & Otomatis</div>
                                    </div>
                                </div>
                                <div class="bg-white p-1.5 rounded-xl shadow-sm border border-gray-200 shrink-0">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-6 object-contain">
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <button type="submit" 
                                style="background-color: #10b981; color: #022c22;"
                                class="w-full py-4 bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-black text-base rounded-2xl shadow-xl shadow-emerald-500/20 transition-all transform active:scale-98 flex items-center justify-center gap-2 cursor-pointer border border-emerald-400">
                            <span class="text-gray-950 font-black">Lanjutkan Pembayaran QRIS</span>
                            <svg class="w-5 h-5 text-gray-950" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Sisi Kanan: Kartu Saldo & Informasi -->
                <div class="lg:col-span-5 space-y-6">
                    <!-- Kartu Saldo Saat Ini -->
                    <div class="relative overflow-hidden rounded-3xl p-6 sm:p-7 bg-gradient-to-br from-emerald-600 to-teal-800 text-white shadow-2xl border border-emerald-400/30">
                        <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-xl pointer-events-none"></div>
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center backdrop-blur-md">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold uppercase tracking-wider text-emerald-100">Saldo Dompet Anda</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full bg-white/20 backdrop-blur-md text-[10px] font-black text-emerald-100">Aktif</span>
                        </div>
                        <div class="text-3xl sm:text-4xl font-black tracking-tight mb-2 text-white font-mono">
                            Rp{{ number_format($user->balance ?? 0, 0, ',', '.') }}
                        </div>
                        <p class="text-xs text-emerald-100/90 font-medium">Bisa digunakan langsung untuk top up game kapan saja tanpa batas kedaluwarsa.</p>
                    </div>

                    <!-- Petunjuk Isi Saldo -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                        <h4 class="font-black text-sm text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Cara Isi Saldo Otomatis:
                        </h4>
                        <ol class="text-xs text-gray-600 dark:text-gray-300 space-y-2.5 list-decimal list-inside font-medium leading-relaxed">
                            <li>Pilih atau ketik nominal saldo yang diinginkan.</li>
                            <li>Klik tombol <strong class="text-emerald-600 dark:text-emerald-400">Lanjutkan Pembayaran</strong>.</li>
                            <li>Scan kode QRIS menggunakan aplikasi e-Wallet (BCA, DANA, GoPay, OVO, ShopeePay, LinkAja, atau Mobile Banking apa saja).</li>
                            <li>Setelah berhasil dibayar, saldo akan langsung masuk detik itu juga!</li>
                        </ol>
                    </div>
                </div>

            </div>

            <!-- Riwayat Deposit Saldo -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-black text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Riwayat Isi Saldo
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Daftar transaksi pengisian saldo dompet akun Anda.</p>
                    </div>
                </div>

                @if($deposits->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider">
                                    <th class="py-3 px-3">No. Deposit</th>
                                    <th class="py-3 px-3">Waktu</th>
                                    <th class="py-3 px-3">Nominal</th>
                                    <th class="py-3 px-3">Metode</th>
                                    <th class="py-3 px-3 text-center">Status</th>
                                    <th class="py-3 px-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium text-gray-800 dark:text-gray-200">
                                @foreach($deposits as $d)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                        <td class="py-3.5 px-3 font-mono font-bold text-gray-900 dark:text-white">{{ $d->id }}</td>
                                        <td class="py-3.5 px-3 text-gray-600 dark:text-gray-300">{{ $d->created_at->format('d M Y, H:i') }}</td>
                                        <td class="py-3.5 px-3 font-black text-emerald-600 dark:text-emerald-400 font-mono text-sm">Rp{{ number_format($d->amount, 0, ',', '.') }}</td>
                                        <td class="py-3.5 px-3 uppercase text-gray-700 dark:text-gray-200 font-bold">{{ $d->payment_method }}</td>
                                        <td class="py-3.5 px-3 text-center">
                                            @if($d->status === 'success' || $d->status === 'paid')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-green-500/10 text-green-600 dark:text-green-400 text-[11px] font-black border border-green-500/20">
                                                    <svg class="w-3 h-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    Sukses Masuk
                                                </span>
                                            @elseif($d->status === 'pending')
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-500/10 text-amber-600 dark:text-amber-400 text-[11px] font-black border border-amber-500/20">
                                                    <svg class="w-3 h-3 text-amber-500 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                    Menunggu Bayar
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full bg-gray-500/10 text-gray-600 dark:text-gray-400 text-[11px] font-bold border border-gray-500/20">
                                                    {{ ucfirst($d->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3.5 px-3 text-right">
                                            @if($d->status === 'pending')
                                                <a href="{{ route('deposit.show', $d->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-md transition">
                                                    <span>Bayar QRIS</span>
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            @else
                                                <span class="text-gray-400 text-xs">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $deposits->links() }}
                    </div>
                @else
                    <div class="py-12 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <p class="font-bold text-sm text-gray-700 dark:text-gray-300">Belum ada riwayat isi saldo.</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Isi saldo Anda sekarang untuk menikmati kemudahan top up game instan!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
