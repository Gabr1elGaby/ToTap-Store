<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200" x-data="{ activeTab: 'software' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-receipt text-indigo-500"></i> Kelola Seluruh Transaksi Pelanggan
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pantau seluruh riwayat transaksi pembelian sistem software, aplikasi POS, dan top up game dari semua pengguna.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-xl bg-blue-50 dark:bg-blue-900/40 border border-blue-200 dark:border-blue-700/50 text-sm font-bold text-blue-600 dark:text-blue-300 shadow-sm">
                        <i class="fas fa-desktop mr-1.5"></i> Software: {{ $orders->total() }}
                    </span>
                    <span class="px-4 py-2 rounded-xl bg-purple-50 dark:bg-purple-900/40 border border-purple-200 dark:border-purple-700/50 text-sm font-bold text-purple-600 dark:text-purple-300 shadow-sm">
                        <i class="fas fa-gamepad mr-1.5"></i> Top Up: {{ $transactions->total() }}
                    </span>
                </div>
            </div>

            <!-- Tab Switcher -->
            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-700 w-fit shadow-sm">
                <button @click="activeTab = 'software'" 
                        :class="activeTab === 'software' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-desktop"></i> Pembelian Sistem & Software ({{ $orders->total() }})
                </button>
                <button @click="activeTab = 'topup'" 
                        :class="activeTab === 'topup' ? 'bg-purple-600 text-white shadow-md' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white'"
                        class="px-5 py-2.5 rounded-xl font-bold text-sm transition flex items-center gap-2">
                    <i class="fas fa-gamepad"></i> Transaksi Top Up Game ({{ $transactions->total() }})
                </button>
            </div>

            <!-- TAB 1: Pembelian Sistem & Software (POS / Software) -->
            <div x-show="activeTab === 'software'" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-slate-50 dark:bg-gray-850">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-desktop text-blue-500"></i> Daftar Pembelian Lisensi Sistem & Software
                    </h2>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ $orders->total() }} Transaksi</span>
                </div>

                @if($orders->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-laptop-code text-5xl mb-3 text-gray-400 dark:text-gray-600"></i>
                        <p class="text-base font-semibold">Belum ada transaksi pembelian sistem atau software.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-3.5 px-6">No. Order</th>
                                    <th class="py-3.5 px-6">Customer / Akun</th>
                                    <th class="py-3.5 px-6">Sistem Software</th>
                                    <th class="py-3.5 px-6">Paket Lisensi</th>
                                    <th class="py-3.5 px-6">Total Biaya</th>
                                    <th class="py-3.5 px-6">Status Bayar</th>
                                    <th class="py-3.5 px-6">Tanggal</th>
                                    <th class="py-3.5 px-6 text-center">Invoice</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                @foreach($orders as $ord)
                                    <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                        <td class="py-4 px-6 font-mono text-xs text-blue-600 dark:text-blue-400 font-bold">
                                            {{ $ord->order_number }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($ord->user)
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $ord->user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $ord->user->email }}</div>
                                                @if($ord->user->phone_number)
                                                    <div class="text-[11px] text-gray-400 font-mono">{{ $ord->user->phone_number }}</div>
                                                @endif
                                            @else
                                                <span class="text-gray-400 italic text-xs">User Terhapus</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                <i class="fas fa-box text-blue-500"></i> {{ $ord->product->name ?? 'Software' }}
                                            </div>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 border border-gray-200 dark:border-gray-600">
                                                {{ $ord->plan->name ?? 'Standard' }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                            Rp{{ number_format($ord->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
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
                                            <a href="{{ route('admin.transactions.invoice', $ord->order_number) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition shadow-sm">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($orders->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- TAB 2: Transaksi Top Up Game (H2H / API Top Up) -->
            <div x-show="activeTab === 'topup'" class="space-y-6">
                <!-- Filter Search & Status -->
                <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[240px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID, Target ID, Provider TRX ID..." 
                                class="w-full bg-slate-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <select name="status" class="bg-slate-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid / Success</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition shadow-md shadow-indigo-600/30">
                                <i class="fas fa-search mr-1.5"></i> Filter
                            </button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.transactions.index') }}" class="p-2 text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Top Up Game Transactions Table -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-slate-50 dark:bg-gray-850">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-gamepad text-purple-500"></i> Transaksi Top Up Game
                        </h2>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Total: {{ $transactions->total() }} Transaksi</span>
                    </div>

                    @if($transactions->isEmpty())
                        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3 text-gray-400 dark:text-gray-600"></i>
                            <p class="text-base font-semibold">Tidak ada transaksi top up ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3.5 px-6">Order ID</th>
                                        <th class="py-3.5 px-6">Customer</th>
                                        <th class="py-3.5 px-6">Game & Item</th>
                                        <th class="py-3.5 px-6">Target ID</th>
                                        <th class="py-3.5 px-6">Total</th>
                                        <th class="py-3.5 px-6">Status Bayar</th>
                                        <th class="py-3.5 px-6">Tanggal</th>
                                        <th class="py-3.5 px-6 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                    @foreach($transactions as $trx)
                                        <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                            <td class="py-4 px-6 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold">
                                                {{ $trx->id }}
                                            </td>
                                            <td class="py-4 px-6">
                                                @if($trx->user)
                                                    <div class="font-bold text-gray-900 dark:text-white">{{ $trx->user->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $trx->user->email }}</div>
                                                @else
                                                    <div class="font-semibold text-gray-500 dark:text-gray-400 italic">Guest</div>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                <div class="font-bold text-gray-900 dark:text-white">{{ $trx->game->name ?? 'Game' }}</div>
                                                <div class="text-xs text-indigo-600 dark:text-indigo-400">{{ $trx->gameProduct->name ?? '-' }}</div>
                                            </td>
                                            <td class="py-4 px-6 font-mono text-xs text-gray-700 dark:text-gray-300">
                                                {{ $trx->target_field_1 }}
                                                @if($trx->target_field_2)
                                                    <span class="text-gray-400">({{ $trx->target_field_2 }})</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 font-bold text-gray-900 dark:text-white whitespace-nowrap">
                                                Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="py-4 px-6 whitespace-nowrap">
                                                @if($trx->status === 'paid' || $trx->status === 'success')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                        <i class="fas fa-check-circle text-[10px]"></i> Sukses
                                                    </span>
                                                @elseif($trx->status === 'pending')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                        <i class="fas fa-clock text-[10px]"></i> Pending
                                                    </span>
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
                                                <a href="{{ route('admin.transactions.invoice', $trx->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition shadow-sm">
                                                    <i class="fas fa-file-invoice"></i> Invoice
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($transactions->hasPages())
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                {{ $transactions->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
