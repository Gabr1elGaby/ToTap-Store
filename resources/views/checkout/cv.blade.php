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
                @if($cv->status === 'PAID')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/60 text-emerald-800 dark:text-emerald-300 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Pembayaran Berhasil / Diterima
                </span>
                @elseif($cv->status === 'FAILED')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-100 dark:bg-red-950/60 border border-red-200 dark:border-red-800/60 text-red-800 dark:text-red-300 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-red-500"></span>
                    Dibatalkan / Ditolak
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-100 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 rounded-full text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    Menunggu Verifikasi Pembayaran
                </span>
                @endif
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

            <!-- RIGHT COLUMN: Payment & Download Status -->
            <div class="lg:col-span-5">
                @if($cv->status === 'PAID')
                <!-- SUCCESS & DOWNLOAD READY CARD -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl p-6 sm:p-8 shadow-xl border-2 border-emerald-500/40 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-400"></div>

                    <div class="w-16 h-16 bg-emerald-100 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                        <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>

                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2">Pembayaran Berhasil!</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-6 leading-relaxed">
                        Pembayaran Anda telah diverifikasi oleh Admin. Dokumen CV PDF resolusi tinggi siap diunduh.
                    </p>

                    <!-- Active Download Button -->
                    <a href="{{ route('cv.download', $cv->access_token ?? $cv->id) }}" 
                       class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-4 px-6 rounded-xl shadow-lg shadow-emerald-600/40 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-3 text-base">
                        <svg class="w-6 h-6 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        <span>Download File PDF CV</span>
                    </a>

                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-center gap-4 text-xs font-semibold">
                        <a href="{{ route('cv.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                            Kembali ke Beranda CV
                        </a>
                    </div>
                </div>
                @else
                <!-- PENDING PAYMENT & VERIFICATION CARD -->
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

                    @php
                        $invNo = $cv->invoice_number ?? ('INV/CV/TTS/' . $cv->id);
                        $adminWa = '6281328972073';
                        $waMsg = "Halo Admin ToTap Store, saya sudah melakukan pembayaran untuk CV & Resume:\n\n"
                               . "• No. Invoice: " . $invNo . "\n"
                               . "• Nama Pemesan: " . $cv->name . "\n"
                               . "• Template: " . ($cv->template_name ?? 'CV') . "\n"
                               . "• Total Tagihan: Rp " . number_format($cv->price, 0, ',', '.') . "\n\n"
                               . "Berikut saya kirimkan bukti transfernya. Mohon bantu di-ACC ya min agar link download PDF aktif. Terima kasih!";
                        $waUrl = "https://wa.me/{$adminWa}?text=" . urlencode($waMsg);
                    @endphp

                    <!-- WhatsApp Confirmation Button -->
                    <a href="{{ $waUrl }}" target="_blank"
                       style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: #ffffff !important;"
                       class="w-full font-bold py-3.5 px-4 rounded-xl shadow-lg shadow-emerald-500/25 transition-all transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-sm sm:text-base mb-3 cursor-pointer">
                        <svg class="w-5 h-5 flex-shrink-0" style="color: #ffffff !important;" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-5.805 1.534zm6.224-3.82c1.516.903 3.327 1.42 5.093 1.421 5.352 0 9.708-4.355 9.71-9.708.002-2.6-1.009-5.044-2.85-6.885-1.84-1.841-4.285-2.85-6.887-2.851-5.353 0-9.708 4.355-9.71 9.708-.001 1.82.518 3.593 1.498 5.126l-1.018 3.717 3.764-.989z"/></svg>
                        <span style="color: #ffffff !important; font-weight: 800;">Saya Sudah Membayar</span>
                    </a>

                    <!-- Locked Download Button -->
                    <div class="mt-2 p-3 bg-slate-50 dark:bg-slate-800/60 rounded-xl border border-slate-200 dark:border-slate-700/60 text-center">
                        <button type="button" disabled class="w-full bg-slate-200 dark:bg-slate-800 text-slate-400 dark:text-slate-500 font-bold py-2.5 px-3 rounded-lg flex items-center justify-center gap-2 text-xs cursor-not-allowed">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            <span>Download PDF (Menunggu ACC Admin)</span>
                        </button>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 leading-relaxed">
                            ⚡ Halaman ini otomatis mengecek status. Begitu Admin meng-ACC pembayaran, tombol download di atas langsung berubah hijau dan aktif.
                        </p>
                    </div>
                </div>

                <!-- Auto Polling Script -->
                <script>
                    const cvToken = "{{ $cv->access_token ?? $cv->id }}";
                    const checkInterval = setInterval(async () => {
                        try {
                            const res = await fetch(`/api/cv/${cvToken}/status`);
                            if (res.ok) {
                                const data = await res.json();
                                if (data.is_paid) {
                                    clearInterval(checkInterval);
                                    window.location.reload();
                                }
                            }
                        } catch (err) {
                            console.error("Status polling error:", err);
                        }
                    }, 4000);
                </script>
                @endif
            </div>

        </div>
    </div>

    <!-- Footer Note -->
    <div class="text-center mt-12 text-xs text-slate-400 dark:text-slate-500">
        &copy; {{ date('Y') }} ToTap Store. Seluruh Hak Cipta Dilindungi.
    </div>

</body>
</html>
