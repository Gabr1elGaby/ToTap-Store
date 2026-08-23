<x-app-layout>
    <div class="py-12 bg-gray-900 min-h-screen text-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                <div>
                    <h1 class="text-2xl font-black text-white flex items-center gap-3">
                        <i class="fas fa-receipt text-indigo-500"></i> Kelola Seluruh Transaksi
                    </h1>
                    <p class="text-sm text-gray-400 mt-1">Pantau, periksa status pembayaran, dan cetak invoice seluruh transaksi pelanggan.</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 rounded-xl bg-gray-900 border border-gray-700 text-sm font-bold text-gray-300">
                        Total: <strong class="text-indigo-400">{{ $transactions->total() }}</strong> Transaksi
                    </span>
                </div>
            </div>

            <!-- Search & Filter Bar -->
            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-xl">
                <form method="GET" action="{{ route('admin.transactions.index') }}" class="grid grid-cols-1 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Cari Transaksi</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID, Nama, Email, ID Game..." 
                                class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:ring-indigo-500 focus:border-indigo-500">
                            <i class="fas fa-search absolute right-3.5 top-3 text-gray-500"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Status Bayar</label>
                        <select name="status" class="w-full bg-gray-900 border border-gray-700 rounded-xl px-4 py-2.5 text-sm text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid (Sukses)</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed (Gagal)</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit" class="w-full px-5 py-2.5 rounded-xl font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition shadow-lg shadow-indigo-600/30">
                            Filter
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('admin.transactions.index') }}" class="px-3 py-2.5 rounded-xl bg-gray-700 hover:bg-gray-600 text-gray-300 transition">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Top Up Game Transactions Table -->
            <div class="bg-gray-800 rounded-2xl border border-gray-700 shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-700 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-white flex items-center gap-2">
                        <i class="fas fa-gamepad text-indigo-400"></i> Transaksi Top Up Game
                    </h2>
                </div>

                @if($transactions->isEmpty())
                    <div class="p-12 text-center text-gray-400">
                        <i class="fas fa-inbox text-5xl mb-3 text-gray-600"></i>
                        <p class="text-base font-semibold">Tidak ada transaksi ditemukan.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-900/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
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
                            <tbody class="divide-y divide-gray-700 text-sm">
                                @foreach($transactions as $trx)
                                    <tr class="hover:bg-gray-750 transition">
                                        <td class="py-4 px-6 font-mono text-xs text-indigo-400 font-bold">
                                            {{ $trx->id }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($trx->user)
                                                <div class="font-bold text-white">{{ $trx->user->name }}</div>
                                                <div class="text-xs text-gray-400">{{ $trx->user->email }}</div>
                                            @else
                                                <div class="font-semibold text-gray-400 italic">Guest</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-white">{{ $trx->game->name ?? 'Game' }}</div>
                                            <div class="text-xs text-indigo-400">{{ $trx->gameProduct->name ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-6 font-mono text-xs text-gray-300">
                                            {{ $trx->target_field_1 }}
                                            @if($trx->target_field_2)
                                                <span class="text-gray-500">({{ $trx->target_field_2 }})</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 font-bold text-white whitespace-nowrap">
                                            Rp{{ number_format($trx->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6 whitespace-nowrap">
                                            @if($trx->status === 'paid' || $trx->status === 'success')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400 border border-green-500/30">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Sukses
                                                </span>
                                            @elseif($trx->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                                    <i class="fas fa-clock text-[10px]"></i> Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-red-500/20 text-red-400 border border-red-500/30">
                                                    <i class="fas fa-times-circle text-[10px]"></i> {{ ucfirst($trx->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-400 whitespace-nowrap">
                                            {{ $trx->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-center whitespace-nowrap">
                                            <a href="{{ route('admin.transactions.invoice', $trx->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition shadow-sm">
                                                <i class="fas fa-file-invoice"></i> Invoice
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($transactions->hasPages())
                        <div class="p-4 border-t border-gray-700">
                            {{ $transactions->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
