<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $type === 'topup' ? $data->id : $data->order_number }} - ToTap Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Righteous&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .invoice-card { border: none !important; shadow: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen p-4 sm:p-8 flex items-center justify-center">

    <div class="max-w-3xl w-full bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden invoice-card">
        
        <!-- Header Actions (No Print) -->
        <div class="no-print bg-gray-50 dark:bg-gray-750 px-8 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <a href="{{ isset($isAdmin) && $isAdmin ? route('admin.transactions.index') : route('transactions.history') }}" class="inline-flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-300 hover:text-indigo-600 transition">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>

        <div class="p-8 sm:p-12 space-y-8">
            
            <!-- Brand & Invoice Info -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 border-b border-gray-200 dark:border-gray-700 pb-8">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-totap-v2.png') }}" class="h-12 w-auto object-contain" alt="ToTap Logo">
                    <div>
                        <div class="text-2xl font-black text-gray-900 dark:text-white tracking-widest" style="font-family: 'Righteous', cursive;">TOTAP STORE</div>
                        <p class="text-xs text-gray-500">Pusat Layanan Digital & Top Up Game</p>
                    </div>
                </div>
                <div class="sm:text-right">
                    <div class="text-xs uppercase font-bold tracking-widest text-indigo-600 dark:text-indigo-400 mb-1">INVOICE RESMI</div>
                    <div class="text-lg font-black font-mono text-gray-900 dark:text-white">
                        {{ $type === 'topup' ? $data->id : $data->order_number }}
                    </div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ $data->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </div>
                </div>
            </div>

            <!-- Customer & Status Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-2">Informasi Pembeli:</span>
                    @if($data->user)
                        <div class="font-bold text-gray-900 dark:text-white text-base">{{ $data->user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $data->user->email }}</div>
                        <div class="text-xs text-gray-500">{{ $data->user->phone_number ?? '-' }}</div>
                    @else
                        <div class="font-bold text-gray-900 dark:text-white text-base">Pelanggan Langsung (Guest)</div>
                        <div class="text-xs text-gray-500">ID: {{ $data->target_field_1 }}</div>
                    @endif
                </div>

                <div class="bg-gray-50 dark:bg-gray-900/50 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-400 block mb-2">Status Pembayaran:</span>
                        @php
                            $status = $type === 'topup' ? $data->status : strtolower($data->payment_status);
                        @endphp
                        @if($status === 'paid' || $status === 'success')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400 border border-green-300 dark:border-green-500/30">
                                <i class="fas fa-check-circle"></i> PEMBAYARAN LUNAS
                            </span>
                        @elseif($status === 'pending')
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 border border-amber-300 dark:border-amber-500/30">
                                <i class="fas fa-clock"></i> MENUNGGU PEMBAYARAN
                            </span>
                        @else
                            <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 border border-red-300 dark:border-red-500/30">
                                <i class="fas fa-times-circle"></i> {{ strtoupper($status) }}
                            </span>
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 mt-2">
                        Metode: <strong class="uppercase text-gray-700 dark:text-gray-300">{{ str_replace('_', ' ', $data->payment_method ?? ($data->gateway ?? 'QRIS')) }}</strong>
                    </div>
                </div>
            </div>

            <!-- Table of Items -->
            <div class="overflow-hidden border border-gray-200 dark:border-gray-700 rounded-2xl">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400 text-xs uppercase tracking-wider">
                            <th class="py-3.5 px-6">Deskripsi Item</th>
                            <th class="py-3.5 px-6 text-center">Tujuan / Lisensi</th>
                            <th class="py-3.5 px-6 text-right">Harga</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @if($type === 'topup')
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $data->game->name ?? 'Game Top Up' }}</div>
                                    <div class="text-xs text-indigo-600 dark:text-indigo-400 font-semibold">{{ $data->gameProduct->name ?? '-' }}</div>
                                    <div class="text-[11px] text-gray-400 font-mono mt-0.5">Kode: {{ $data->gameProduct->product_code ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-mono text-xs bg-gray-100 dark:bg-gray-900 px-2.5 py-1 rounded-lg border border-gray-200 dark:border-gray-700">
                                        {{ $data->target_field_1 }} {{ $data->target_field_2 ? "({$data->target_field_2})" : '' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-gray-900 dark:text-white">
                                    Rp{{ number_format($data->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-900 dark:text-white">{{ $data->product->name ?? 'Software' }}</div>
                                    <div class="text-xs text-blue-600 dark:text-blue-400 font-semibold">{{ $data->plan->name ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center text-xs text-gray-500">
                                    Paket Berlangganan
                                </td>
                                <td class="py-4 px-6 text-right font-bold text-gray-900 dark:text-white">
                                    Rp{{ number_format($data->amount, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>

            <!-- Price Breakdown -->
            <div class="flex justify-end">
                <div class="w-full sm:w-1/2 space-y-2 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal:</span>
                        <span class="font-semibold text-gray-800 dark:text-gray-200">Rp{{ number_format($data->amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-gray-500">
                        <span>Biaya Layanan:</span>
                        <span class="font-semibold text-green-500">Gratis (Rp0)</span>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between items-center">
                        <span class="text-base font-bold text-gray-900 dark:text-white">Total Tagihan:</span>
                        <span class="text-xl font-black text-indigo-600 dark:text-indigo-400">Rp{{ number_format($data->amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer Notes -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 text-center text-xs text-gray-500 leading-relaxed">
                <p class="font-semibold text-gray-700 dark:text-gray-300">Terima kasih telah berbelanja di ToTap Store!</p>
                <p>Invoice ini dibuat secara otomatis oleh sistem komputer dan sah sebagai bukti pembayaran yang sah.</p>
                <p class="mt-2 text-gray-400">Butuh bantuan? Hubungi WhatsApp Layanan Pelanggan di website resmi kami.</p>
            </div>

        </div>
    </div>

</body>
</html>
