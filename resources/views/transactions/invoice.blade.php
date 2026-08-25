<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $type === 'topup' ? $data->id : $data->order_number }} - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    <script>
        // Init theme immediately to prevent flashing
        if (localStorage.getItem('totap_theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Righteous&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .invoice-card { border: none !important; box-shadow: none !important; width: 100% !important; max-width: 100% !important; }
            .dark * { color: black !important; background-color: transparent !important; }
        }
    </style>
</head>
<body class="bg-slate-100 dark:bg-slate-950 text-slate-800 dark:text-slate-100 min-h-screen p-4 sm:p-8 flex items-center justify-center transition-colors duration-200">

    <div class="max-w-3xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden invoice-card">
        
        <!-- Header Actions (No Print) -->
        <div class="no-print bg-slate-50 dark:bg-slate-800/80 px-6 sm:px-8 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between flex-wrap gap-3">
            <a href="{{ isset($isAdmin) && $isAdmin ? route('admin.transactions.index') : route('transactions.history') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 dark:text-slate-200 hover:text-indigo-600 dark:hover:text-indigo-400 transition px-3 py-1.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            
            <div class="flex items-center gap-2.5">
                <!-- Theme Toggle Button -->
                <button onclick="toggleTheme()" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-sm font-bold text-slate-700 dark:text-slate-200 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-slate-100 dark:hover:bg-slate-600 transition">
                    <span id="themeIcon" class="text-amber-500"><i class="fas fa-sun"></i></span>
                    <span id="themeText" class="text-xs">Tema Terang</span>
                </button>

                <!-- Print / Save PDF Button -->
                <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-md shadow-indigo-600/20 transition">
                    <i class="fas fa-print"></i> Cetak / Simpan PDF
                </button>
            </div>
        </div>

        <div class="p-6 sm:p-10 md:p-12 space-y-8">
            
            <!-- Brand & Invoice Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-b border-slate-200 dark:border-slate-800 pb-8">
                <div class="flex items-center gap-3.5">
                    <img src="{{ asset('images/logo-totap-v2.png') }}" class="h-12 w-auto object-contain drop-shadow-sm" alt="ToTap Logo">
                    <div>
                        <div class="text-2xl font-black text-slate-900 dark:text-white tracking-widest" style="font-family: 'Righteous', cursive;">TOTAP STORE</div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Pusat Layanan Digital & Top Up Game</p>
                    </div>
                </div>
                <div class="sm:text-right">
                    <div class="text-xs uppercase font-extrabold tracking-widest text-indigo-600 dark:text-indigo-400 mb-1">INVOICE RESMI</div>
                    <div class="text-lg font-black font-mono text-slate-900 dark:text-white">
                        {{ $type === 'topup' ? $data->id : $data->order_number }}
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                        {{ $data->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>

            <!-- Customer & Status Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 block mb-2">Informasi Pembeli:</span>
                    @if($data->user)
                        <div class="font-bold text-slate-900 dark:text-white text-base">{{ $data->user->name }}</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ $data->user->email }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $data->user->phone_number ?? '-' }}</div>
                    @else
                        <div class="font-bold text-slate-900 dark:text-white text-base">Pelanggan Langsung (Guest)</div>
                        <div class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">ID: {{ $data->target_field_1 }}</div>
                    @endif
                </div>

                <div class="bg-slate-50 dark:bg-slate-800/50 p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-400 dark:text-slate-400 block mb-2">Status Pembayaran:</span>
                        @php
                            $status = $type === 'topup' ? $data->status : strtolower($data->payment_status);
                        @endphp
                        @if($status === 'paid' || $status === 'success')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-950/60 text-green-700 dark:text-green-300 border border-green-300 dark:border-green-800">
                                <i class="fas fa-check-circle"></i> PEMBAYARAN LUNAS
                            </span>
                        @elseif($status === 'pending')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-300 dark:border-amber-800">
                                <i class="fas fa-clock"></i> MENUNGGU PEMBAYARAN
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800">
                                <i class="fas fa-times-circle"></i> {{ strtoupper($status) }}
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-3">
                        Metode: <strong class="uppercase text-slate-800 dark:text-slate-200">{{ str_replace('_', ' ', $data->payment_method ?? ($data->gateway ?? 'QRIS')) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Table of Items -->
            <div class="overflow-hidden border border-slate-200 dark:border-slate-800 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-xs uppercase tracking-wider font-bold">
                            <th class="py-3.5 px-6">Deskripsi Item</th>
                            <th class="py-3.5 px-6 text-center">Tujuan / Lisensi</th>
                            <th class="py-3.5 px-6 text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-800 text-sm">
                        @if($type === 'topup')
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white text-base">{{ $data->game->name ?? 'Game Top Up' }}</div>
                                    <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">{{ $data->gameProduct->name ?? '-' }}</div>
                                    <div class="text-[11px] text-slate-400 font-mono mt-0.5">Kode: {{ $data->gameProduct->product_code ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-mono text-xs bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-2.5 py-1 rounded-lg border border-slate-200 dark:border-slate-700">
                                        {{ $data->target_field_1 }} {{ $data->target_field_2 ? "({$data->target_field_2})" : '' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-extrabold text-slate-900 dark:text-white">
                                    Rp{{ number_format($data->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-slate-900 dark:text-white text-base">{{ $data->product->name ?? 'Software' }}</div>
                                    <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold mt-0.5">{{ $data->plan->name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center text-xs text-slate-500 dark:text-slate-400">
                                    Paket Berlangganan
                                </td>
                                <td class="py-4 px-6 text-right font-extrabold text-slate-900 dark:text-white">
                                    Rp{{ number_format($data->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Price Breakdown -->
            <div class="flex justify-end">
                <div class="w-full sm:w-1/2 space-y-2.5 text-sm">
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Subtotal:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">Rp{{ number_format($data->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500 dark:text-slate-400">
                        <span>Biaya Layanan:</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">Gratis (Rp0)</span>
                    </div>
                    <div class="border-t border-slate-200 dark:border-slate-800 pt-3 flex justify-between items-center">
                        <span class="text-base font-bold text-slate-900 dark:text-white">Total Tagihan:</span>
                        <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">Rp{{ number_format($data->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="border-t border-slate-200 dark:border-slate-800 pt-6 text-center text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                <p class="font-bold text-slate-700 dark:text-slate-300">Terima kasih telah berbelanja di ToTap Store!</p>
                <p>Invoice ini dibuat secara otomatis oleh sistem komputer dan sah sebagai bukti pembayaran resmi.</p>
                <p class="mt-2 text-slate-400 dark:text-slate-500">Butuh bantuan? Hubungi WhatsApp Layanan Pelanggan di website resmi kami.</p>
            </div>

        </div>
    </div>

    <script>
        function updateThemeUI() {
            const isDark = document.documentElement.classList.contains('dark');
            const icon = document.getElementById('themeIcon');
            const text = document.getElementById('themeText');
            if (isDark) {
                icon.innerHTML = '<i class="fas fa-sun"></i>';
                icon.className = 'text-amber-400';
                text.textContent = 'Tema Terang';
            } else {
                icon.innerHTML = '<i class="fas fa-moon"></i>';
                icon.className = 'text-indigo-600';
                text.textContent = 'Tema Gelap';
            }
        }

        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('totap_theme', 'light');
            } else {
                document.documentElement.classList.add('dark');
                localStorage.setItem('totap_theme', 'dark');
            }
            updateThemeUI();
        }

        // Run UI update on load
        document.addEventListener('DOMContentLoaded', updateThemeUI);
    </script>
</body>
</html>
