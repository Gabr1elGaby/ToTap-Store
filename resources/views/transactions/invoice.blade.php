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
                    <div class="text-base sm:text-lg font-black font-mono text-slate-900 dark:text-white">
                        {{ $data->invoice_number ?? ($type === 'topup' ? $data->id : $data->order_number) }}
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

            @if($type === 'topup' && !empty($data->provider_sn))
            @php
                $acc = \App\Helpers\InvoiceHelper::parseAccountCredentials($data->provider_sn);
            @endphp
            <!-- Informasi Akun / Serial Number Resmi (Aplikasi Premium / Voucher) -->
            <div class="bg-amber-500/10 dark:bg-amber-950/40 border-2 border-amber-500 rounded-2xl p-5 sm:p-6 shadow-md space-y-4">
                <div class="flex items-center justify-between border-b border-amber-500/30 pb-3">
                    <div class="flex items-center gap-2 font-black text-amber-950 dark:text-amber-300 text-sm sm:text-base">
                        <i class="fas fa-key text-amber-500 text-base"></i> INFORMASI AKUN / AKSES RESMI
                    </div>
                    <span class="text-xs font-black bg-amber-500 text-white dark:text-slate-900 px-3 py-1 rounded-full uppercase tracking-wider shadow-sm">
                        Aktif & Resmi
                    </span>
                </div>

                @if($acc['is_structured'] && !empty($acc['items']))
                <div class="space-y-3">
                    @foreach($acc['items'] as $label => $val)
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-wider text-slate-800 dark:text-amber-200 mb-1">
                            {{ $label }}:
                        </label>
                        @php
                            $isUrl = str_starts_with($val, 'http://') || str_starts_with($val, 'https://');
                            $isPass = stripos($label, 'password') !== false || stripos($label, 'pass') !== false;
                            $isProfile = stripos($label, 'profil') !== false || stripos($label, 'pin') !== false;
                        @endphp
                        <div class="p-3 bg-slate-900 dark:bg-slate-950 rounded-xl border-2 border-amber-500/40 font-mono text-xs font-bold {{ $isUrl ? 'text-blue-300' : ($isPass ? 'text-emerald-400' : ($isProfile ? 'text-purple-300' : 'text-amber-300')) }} select-all flex items-center justify-between gap-3 shadow-inner">
                            @if($isUrl)
                            <a href="{{ $val }}" target="_blank" class="truncate text-blue-400 hover:underline flex items-center gap-1">
                                {{ $val }} <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($val) }}'); alert('Link disalin!');" class="no-print px-2.5 py-1.5 text-xs font-black text-white bg-slate-700 hover:bg-slate-600 active:scale-95 rounded-lg shadow-md transition whitespace-nowrap cursor-pointer flex items-center gap-1">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <a href="{{ $val }}" target="_blank" class="no-print px-3 py-1.5 text-xs font-black text-white bg-blue-600 hover:bg-blue-700 active:scale-95 rounded-lg shadow-md transition whitespace-nowrap cursor-pointer flex items-center gap-1.5">
                                    <i class="fas fa-external-link-alt"></i> Buka Link
                                </a>
                            </div>
                            @else
                            <span class="break-all leading-relaxed">{{ $val }}</span>
                            <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($val) }}'); alert('{{ $label }} berhasil disalin!');" class="no-print px-3 py-1.5 text-xs font-black text-white {{ $isPass ? 'bg-emerald-600 hover:bg-emerald-700' : ($isProfile ? 'bg-purple-600 hover:bg-purple-700' : 'bg-amber-600 hover:bg-amber-700') }} active:scale-95 rounded-lg shadow-md transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 shrink-0">
                                <i class="fas fa-copy"></i> Salin
                            </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="space-y-2">
                    <label class="block text-xs font-black uppercase tracking-wider text-slate-800 dark:text-amber-200">Detail Login / Serial Number:</label>
                    <div class="p-3.5 bg-slate-900 dark:bg-slate-950 rounded-xl border-2 border-amber-500/40 font-mono text-xs font-bold text-amber-300 break-all select-all flex items-center justify-between gap-3 shadow-inner">
                        <span class="leading-relaxed">{{ $data->provider_sn }}</span>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ addslashes($data->provider_sn) }}'); alert('Detail akun berhasil disalin ke clipboard!');" class="no-print px-3.5 py-2 text-xs font-black text-white bg-amber-600 hover:bg-amber-700 active:scale-95 rounded-lg shadow-md transition whitespace-nowrap cursor-pointer flex items-center gap-1.5 shrink-0">
                            <i class="fas fa-copy"></i> Salin
                        </button>
                    </div>
                </div>
                @endif

                <p class="text-xs font-medium text-slate-800 dark:text-slate-200 pt-2.5 border-t border-amber-500/30">
                    💡 <em>Gunakan detail akun di atas untuk login ke aplikasi. Jika tertera link panduan (URL), klik link tersebut untuk panduan aktivasi profil.</em>
                </p>
            </div>
            @endif

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

            @if($status === 'paid' || $status === 'success')
            <!-- Customer Rating & Super Admin Feedback Box (No Print) -->
            <div id="invoice-review-card" class="no-print bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 rounded-2xl p-6 shadow-sm">
                <div class="text-center mb-4">
                    <span class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400">Kepuasan Pelanggan</span>
                    <h4 class="text-base font-bold text-slate-900 dark:text-white mt-0.5">Beri Rating & Kritik / Saran</h4>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Kritik & saran Anda hanya akan dibaca secara privat oleh Super Admin untuk terus meningkatkan performa website & layanan.</p>
                </div>

                <form id="invoiceReviewForm" onsubmit="submitInvoiceReview(event)" class="max-w-md mx-auto space-y-4">
                    <input type="hidden" name="order_id" value="{{ $data->invoice_number ?? ($type === 'topup' ? $data->id : $data->order_number) }}">
                    <input type="hidden" name="order_type" value="{{ $type === 'topup' ? 'topup' : 'software' }}">
                    <input type="hidden" name="customer_name" value="{{ $data->user->name ?? 'Pelanggan ToTap' }}">
                    <input type="hidden" name="customer_contact" value="{{ $data->user->email ?? ($data->user->phone_number ?? '') }}">
                    <input type="hidden" name="product_name" value="{{ $type === 'topup' ? ($data->game->name ?? 'Top Up Game') : ($data->product->name ?? 'Software') }}">
                    <input type="hidden" id="invoice-selected-rating" name="rating" value="5">

                    <!-- Star Rating -->
                    <div class="flex flex-col items-center justify-center gap-1">
                        <div class="flex items-center gap-2 text-3xl cursor-pointer" id="invoice-star-container">
                            <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setInvoiceRating(1)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setInvoiceRating(2)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setInvoiceRating(3)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setInvoiceRating(4)">★</span>
                            <span class="star text-amber-400 transition transform hover:scale-125 select-none" onclick="setInvoiceRating(5)">★</span>
                        </div>
                        <span id="invoice-rating-label" class="text-xs font-bold text-amber-500 mt-1">5/5 - Sangat Puas! ⭐</span>
                    </div>

                    <!-- Review Textarea -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1">Kritik & Saran Khusus Super Admin:</label>
                        <textarea name="review_text" rows="3" placeholder="Tuliskan pengalaman bertransaksi, kritik membangun, atau saran fitur baru untuk Super Admin..." class="w-full text-xs p-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                    </div>

                    <button type="submit" id="invoice-submit-review-btn" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-xl text-xs shadow-md transition">
                        Kirim Rating & Masukan ⭐
                    </button>
                </form>

                <div id="invoice-review-success" class="hidden text-center py-4 space-y-2">
                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-xl font-bold">
                        ✓
                    </div>
                    <h5 class="text-sm font-bold text-slate-900 dark:text-white">Terima Kasih Atas Ulasan & Masukan Anda!</h5>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Rating & saran Anda telah berhasil dikirim langsung ke Super Admin.</p>
                </div>
            </div>

            <script>
                const invoiceRatingLabels = {
                    1: '1/5 - Sangat Buruk',
                    2: '2/5 - Kurang Memuaskan',
                    3: '3/5 - Cukup',
                    4: '4/5 - Bagus & Cepat',
                    5: '5/5 - Sangat Puas! ⭐'
                };

                function setInvoiceRating(num) {
                    document.getElementById('invoice-selected-rating').value = num;
                    document.getElementById('invoice-rating-label').innerText = invoiceRatingLabels[num] || `${num}/5`;
                    
                    const stars = document.querySelectorAll('#invoice-star-container .star');
                    stars.forEach((star, idx) => {
                        if (idx < num) {
                            star.classList.remove('text-gray-300', 'dark:text-gray-600');
                            star.classList.add('text-amber-400');
                        } else {
                            star.classList.remove('text-amber-400');
                            star.classList.add('text-gray-300', 'dark:text-gray-600');
                        }
                    });
                }

                function submitInvoiceReview(e) {
                    e.preventDefault();
                    const form = document.getElementById('invoiceReviewForm');
                    const formData = new FormData(form);
                    const btn = document.getElementById('invoice-submit-review-btn');
                    btn.disabled = true;
                    btn.innerText = 'Mengirim...';

                    fetch('/api/reviews', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(res => res.json())
                    .then(data => {
                        form.classList.add('hidden');
                        document.getElementById('invoice-review-success').classList.remove('hidden');
                    })
                    .catch(() => {
                        form.classList.add('hidden');
                        document.getElementById('invoice-review-success').classList.remove('hidden');
                    });
                }
            </script>
            @endif

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
