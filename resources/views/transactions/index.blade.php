<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200" x-data="{ activeTab: 'all' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-history text-indigo-500"></i> Riwayat Transaksi Anda
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pantau seluruh riwayat pembelian lisensi software, sistem aplikasi, dan top up game Anda.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="/software" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition shadow-md shadow-blue-600/30">
                        <i class="fas fa-desktop"></i> Beli Software
                    </a>
                    <a href="{{ route('topup.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-md shadow-indigo-600/30">
                        <i class="fas fa-gamepad"></i> Top Up Game
                    </a>
                </div>
            </div>

            <!-- Tab Buttons -->
            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-700 w-fit shadow-sm">
                <button @click="activeTab = 'all'" 
                        :class="activeTab === 'all' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition">
                    Semua Transaksi
                </button>
                <button @click="activeTab = 'software'" 
                        :class="activeTab === 'software' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-desktop text-blue-500"></i> Pembelian Sistem & Software ({{ $orders->total() }})
                </button>
                <button @click="activeTab = 'topup'" 
                        :class="activeTab === 'topup' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-gamepad text-purple-500"></i> Top Up Game ({{ $topups->total() }})
                </button>
            </div>

            <!-- Section 1: Pembelian Sistem / Software / POS -->
            <div x-show="activeTab === 'all' || activeTab === 'software'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-slate-50 dark:bg-gray-850">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-desktop text-blue-500"></i> Pembelian Sistem & Lisensi Software
                    </h2>
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-bold bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">
                        {{ $orders->total() }} Pesanan
                    </span>
                </div>

                @if($orders->isEmpty())
                    <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-laptop-code text-4xl mb-3 text-gray-400 dark:text-gray-600"></i>
                        <p class="text-sm font-semibold">Belum ada pembelian sistem software atau lisensi POS.</p>
                        <a href="/software" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-1 inline-block">Lihat Katalog Software Kami &rarr;</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3.5 px-6">No. Order</th>
                                    <th class="py-3.5 px-6">Sistem / Software</th>
                                    <th class="py-3.5 px-6">Paket Lisensi</th>
                                    <th class="py-3.5 px-6">Total Biaya</th>
                                    <th class="py-3.5 px-6">Status Bayar</th>
                                    <th class="py-3.5 px-6">Tanggal</th>
                                    <th class="py-3.5 px-6 text-center">Aksi / Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($orders as $ord)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-750 transition">
                                        <td class="py-4 px-6 font-mono text-xs text-blue-600 dark:text-blue-400 font-bold">
                                            {{ $ord->order_number }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                <i class="fas fa-box text-blue-500"></i> {{ $ord->product->name ?? 'Software' }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                                {{ $ord->plan->name ?? 'Standard Plan' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                            Rp{{ number_format($ord->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($ord->payment_status === 'PAID')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Lunas / Aktif
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                    <i class="fas fa-clock text-[10px]"></i> {{ $ord->payment_status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $ord->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ route('transactions.invoice', $ord->order_number) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition shadow-sm">
                                                    <i class="fas fa-file-invoice"></i> Invoice
                                                </a>
                                                @if($ord->payment_status === 'PAID' && $ord->product && $ord->product->slug === 'totap-pos')
                                                    <a href="https://totap-kasir-production.up.railway.app" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl text-xs font-bold text-green-600 dark:text-green-400 bg-green-500/10 hover:bg-green-500/20 border border-green-500/30 transition shadow-sm">
                                                        <i class="fas fa-external-link-alt"></i> Buka POS
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Section 2: Top Up Game Transactions -->
            <div x-show="activeTab === 'all' || activeTab === 'topup'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-slate-50 dark:bg-gray-850">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-gamepad text-purple-500"></i> Transaksi Top Up Game
                    </h2>
                    <span class="text-xs text-purple-600 dark:text-purple-400 font-bold bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">
                        {{ $topups->total() }} Transaksi
                    </span>
                </div>

                @if($topups->isEmpty())
                    <div class="p-10 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-receipt text-4xl mb-3 text-gray-400 dark:text-gray-600"></i>
                        <p class="text-sm font-semibold">Belum ada riwayat top up game.</p>
                        <a href="{{ route('topup.index') }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline mt-1 inline-block">Top Up Sekarang &rarr;</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
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
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($topups as $trx)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-750 transition">
                                        <td class="py-4 px-6 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                                            {{ $trx->id }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $trx->game->name ?? 'Game' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->gameProduct->name ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-700 dark:text-gray-300">
                                            {{ $trx->target_field_1 }}
                                            @if($trx->target_field_2)
                                                <span class="text-gray-400">({{ $trx->target_field_2 }})</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 uppercase text-xs font-semibold text-gray-700 dark:text-gray-300">
                                            {{ str_replace('_', ' ', $trx->payment_method ?? 'QRIS') }}
                                        </td>
                                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white">
                                            Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($trx->status === 'paid' || $trx->status === 'success')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Sukses
                                                </span>
                                            @elseif($trx->status === 'pending')
                                                <a href="{{ route('topup.checkout.show', $trx->id) }}" class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 hover:bg-amber-500/20 transition">
                                                    <i class="fas fa-clock text-[10px]"></i> Bayar Sekarang
                                                </a>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20">
                                                    <i class="fas fa-times-circle text-[10px]"></i> {{ ucfirst($trx->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $trx->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <a href="{{ route('transactions.invoice', $trx->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition shadow-sm">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($topups->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $topups->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
