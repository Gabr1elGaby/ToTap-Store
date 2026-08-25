<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Invoice & Pembayaran QRIS - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen py-8 px-4 sm:px-6 lg:px-8 flex flex-col justify-between">
    
    <div class="max-w-4xl mx-auto w-full">
        <!-- Header Brand -->
        <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-200 dark:border-slate-800">
            <a href="{{ url('/') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store Logo" class="h-11 w-auto object-contain drop-shadow-md group-hover:scale-105 transition-transform duration-200">
                <div>
                    <h1 class="text-xl font-extrabold text-slate-900 dark:text-white leading-tight flex items-center gap-2">
                        ToTap Store
                    </h1>
                    <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Official Payment & Invoice Gateway</p>
                </div>
            </a>
            <div class="text-right">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Pembayaran
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <!-- LEFT COLUMN: Invoice Information -->
            <div class="lg:col-span-7 space-y-6">
                <!-- Invoice Box -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-7 shadow-sm border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100 dark:border-slate-800">
                        <div>
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Nomor Invoice</span>
                            <p class="text-lg font-mono font-bold text-slate-900 dark:text-white">{{ $cv->invoice_number ?? ('INV/CV/TTS/' . str_pad($cv->id, 3, '0', STR_PAD_LEFT) . '/' . \App\Helpers\InvoiceHelper::getRomanMonth(\Carbon\Carbon::parse($cv->created_at ?? now())->month) . '/' . \Carbon\Carbon::parse($cv->created_at ?? now())->year) }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Waktu Pemesanan</span>
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($cv->created_at ?? now())->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <div class="mb-6 bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 border border-slate-100 dark:border-slate-800">
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Informasi Pemesan</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                            <div>
                                <span class="text-xs text-slate-400 block">Nama Lengkap</span>
                                <span class="font-bold text-slate-900 dark:text-white">{{ $cv->name }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-400 block">Email Pengguna</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200 break-all">{{ $cv->email }}</span>
                            </div>
                            @if(!empty($cv->phone))
                            <div>
                                <span class="text-xs text-slate-400 block">No. WhatsApp / HP</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $cv->phone }}</span>
                            </div>
                            @endif
                            @if(!empty($cv->job_title))
                            <div>
                                <span class="text-xs text-slate-400 block">Posisi / Job Title</span>
                                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $cv->job_title }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Item Breakdown -->
                    <div>
                        <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Rincian Pembelian</h4>
                        <div class="flex items-center justify-between py-3 border-b border-slate-100 dark:border-slate-800">
                            <div>
                                <p class="font-bold text-slate-900 dark:text-white text-sm">Template CV: {{ $cv->template_name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Lisensi Unduh PDF Berkualitas Tinggi (Siap Kerja & ATS)</p>
                            </div>
                            <span class="font-bold text-slate-900 dark:text-white text-sm">Rp{{ number_format($cv->price, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5 text-xs text-slate-500 dark:text-slate-400">
                            <span>Biaya Layanan & Gateway</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold">GRATIS (Rp0)</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 mt-2 border-t border-slate-200 dark:border-slate-700">
                            <span class="text-base font-extrabold text-slate-900 dark:text-white">Total Tagihan</span>
                            <span class="text-2xl font-black text-blue-600 dark:text-blue-400">Rp{{ number_format($cv->price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Back button -->
                <div class="flex items-center justify-between text-sm">
                    <a href="{{ route('cv.create') }}?template={{ $cv->template_slug ?? 'modern' }}" class="text-blue-600 dark:text-blue-400 hover:underline font-bold inline-flex items-center gap-1.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Ubah Data CV
                    </a>
                    <span class="text-xs text-slate-400">🔒 Transaksi Aman & Terenkripsi</span>
                </div>
            </div>

            <!-- RIGHT COLUMN: QRIS Payment Gateway -->
            <div class="lg:col-span-5">
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-7 shadow-lg border-2 border-blue-500/30 dark:border-blue-500/20 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500"></div>

                    <!-- Header QRIS -->
                    <div class="flex items-center justify-center gap-2 mb-3">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS" class="h-7 object-contain">
                    </div>
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mb-5">
                        Scan menggunakan M-Banking / E-Wallet apa pun. <br>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-bold rounded-full mt-2 text-xs border border-emerald-200 dark:border-emerald-800">
                            ⚡ Nominal Terisi Otomatis: Rp{{ number_format($cv->price, 0, ',', '.') }}
                        </span>
                    </p>

                    <!-- QR Box -->
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-inner border border-slate-200 dark:border-slate-700 mb-4">
                        @php
                            $dynamicQris = \App\Helpers\QrisHelper::getDynamicQrisForAmount((int) $cv->price);
                            $qrSrc = 'https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($dynamicQris);
                        @endphp
                        <img src="{{ $qrSrc }}" alt="QRIS Dinamis ToTap Store" class="w-52 h-52 mx-auto rounded-lg">
                        <div class="mt-3 text-center space-y-0.5">
                            <span class="text-xs font-black text-gray-900 block">TOTAP STORE, GAMING</span>
                            <span class="text-[11px] font-mono text-gray-600 block">NMID: ID1026577601523</span>
                        </div>
                    </div>

                    <!-- Supported Badges -->
                    <div class="flex flex-wrap items-center justify-center gap-2 mb-6">
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">GoPay</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">OVO</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">DANA</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">ShopeePay</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">BCA Mobile</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">Livin'</span>
                        <span class="px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-[10px] font-bold text-slate-600 dark:text-slate-300 rounded">BRImo</span>
                    </div>

                    <!-- Confirm Payment Form -->
                    <form action="{{ route('cv.payment.simulate', $cv->access_token ?? $cv->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-blue-600/30 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-sm sm:text-base">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span>Saya Sudah Membayar</span>
                        </button>
                    </form>

                    <p class="text-[11px] text-slate-400 dark:text-slate-500 mt-3">
                        Setelah pembayaran berhasil diverifikasi, file PDF CV Anda akan otomatis diunduh.
                    </p>
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Note -->
    <div class="text-center mt-12 text-xs text-slate-400 dark:text-slate-500">
        &copy; {{ date('Y') }} ToTap Store. Seluruh Hak Cipta Dilindungi.
    </div>

</body>
</html>
