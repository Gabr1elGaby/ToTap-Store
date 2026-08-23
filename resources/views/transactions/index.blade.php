<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                <div>
                    <h1 class="text-2xl font-black text-white flex items-center gap-3">
                        <i class="fas fa-history text-indigo-500"></i> Riwayat Transaksi
                    </h1>
                    <p class="text-sm text-gray-400 mt-1">Lihat seluruh riwayat pembelian top up game dan produk digital Anda.</p>
                </div>
                <a href="{{ route('topup.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">
                    <i class="fas fa-gamepad"></i> Top Up Lagi
                </a>
            </div>

            <!-- Top Up Game Transactions -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-yellow-400"></i> Top Up Game
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Total: {{ $topups->total() }} Transaksi</span>
                </div>

                @if($topups->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        <i class="fas fa-receipt text-5xl mb-3 text-gray-600"></i>
                        <p class="text-base font-semibold">Belum ada riwayat top up game.</p>
                        <p class="text-xs text-gray-500 mt-1">Lakukan pembelian top up pertama Anda sekarang.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-900/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                    <th class="py-3.5 px-6">Order ID</th>
                                    <th class="py-3.5 px-6">Game / Item</th>
                                    <th class="py-3.5 px-6">Tujuan (ID)</th>
                                    <th class="py-3.5 px-6">Metode</th>
                                    <th class="py-3.5 px-6">Total</th>
                                    <th class="py-3.5 px-6">Status</th>
                                    <th class="py-3.5 px-6">Tanggal</th>
                                    <th class="py-3.5 px-6 text-center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700 text-sm">
                                @foreach($topups as $trx)
                                    <tr class="hover:bg-gray-750 transition">
                                        <td class="py-4 px-6 font-mono text-xs text-indigo-400 font-bold">
                                            {{ $trx->id }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-white">{{ $trx->game->name ?? 'Game' }}</div>
                                            <div class="text-xs text-gray-400">{{ $trx->gameProduct->name ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-300">
                                            {{ $trx->target_field_1 }}
                                            @if($trx->target_field_2)
                                                <span class="text-gray-500">({{ $trx->target_field_2 }})</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 uppercase text-xs font-semibold text-gray-300">
                                            {{ str_replace('_', ' ', $trx->payment_method ?? 'QRIS') }}
                                        </td>
                                        <td class="py-4 px-6 font-bold text-white">
                                            Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($trx->status === 'paid' || $trx->status === 'success')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Sukses
                                                </span>
                                            @elseif($trx->status === 'pending')
                                                <a href="{{ route('topup.checkout.show', $trx->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 hover:bg-amber-500/30 transition">
                                                    <i class="fas fa-clock text-[10px]"></i> Bayar Sekarang
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                                    <i class="fas fa-times-circle text-[10px]"></i> {{ ucfirst($trx->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-400 whitespace-nowrap">
                                            {{ $trx->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            <a href="{{ route('transactions.invoice', $trx->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($topups->hasPages())
                        <div class="p-4 border-t border-gray-700">
                            {{ $topups->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- Software / License Orders (Jika Ada) -->
            @if($orders->isNotEmpty())
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-desktop text-blue-400"></i> Langganan Software / Lisensi POS
                    </h2>
                    <span class="text-xs text-gray-400 font-medium">Total: {{ $orders->total() }} Pesanan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                <th class="py-3.5 px-6">No. Order</th>
                                <th class="py-3.5 px-6">Software / Paket</th>
                                <th class="py-3.5 px-6">Total</th>
                                <th class="py-3.5 px-6">Status Bayar</th>
                                <th class="py-3.5 px-6">Tanggal</th>
                                <th class="py-3.5 px-6 text-center">Invoice</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700 text-sm">
                            @foreach($orders as $ord)
                                <tr class="hover:bg-gray-750 transition">
                                    <td class="py-4 px-6 font-mono text-xs text-blue-400 font-bold">
                                        {{ $ord->order_number }}
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="font-bold text-white">{{ $ord->product->name ?? 'Software' }}</div>
                                        <div class="text-xs text-gray-400">{{ $ord->plan->name ?? '-' }}</div>
                                    </td>
                                    <td class="py-4 px-6 font-bold text-white">
                                        Rp{{ number_format($ord->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($ord->payment_status === 'PAID')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                                                <i class="fas fa-check-circle text-[10px]"></i> Lunas
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                                <i class="fas fa-clock text-[10px]"></i> {{ $ord->payment_status }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-xs text-gray-400 whitespace-nowrap">
                                        {{ $ord->created_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('transactions.invoice', $ord->order_number) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition">
                                            <i class="fas fa-file-invoice"></i> Invoice
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

        </div>
    </div>
</x-app-layout>
