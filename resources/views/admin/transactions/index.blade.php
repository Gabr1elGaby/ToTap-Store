<x-app-layout>
    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Header Card -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl transition-colors duration-200">
                <div>
                    <h1 class="text-2xl font-black text-gray-900 dark:text-white flex items-center gap-3">
                        <i class="fas fa-receipt text-indigo-500"></i> Kelola Seluruh Transaksi Pelanggan
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Pantau seluruh riwayat transaksi pembelian sistem software, aplikasi POS, dan top up game dari semua pengguna.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <span class="px-4 py-2.5 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800/80 text-sm font-bold text-blue-600 dark:text-blue-300 shadow-sm">
                        <i class="fas fa-desktop mr-1.5"></i> Software: {{ $orders->total() }}
                    </span>
                    <span class="px-4 py-2.5 rounded-2xl bg-purple-50 dark:bg-purple-950/60 border border-purple-200 dark:border-purple-800/80 text-sm font-bold text-purple-600 dark:text-purple-300 shadow-sm">
                        <i class="fas fa-gamepad mr-1.5"></i> Top Up: {{ $transactions->total() }}
                    </span>
                    <span class="px-4 py-2.5 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800/80 text-sm font-bold text-amber-600 dark:text-amber-300 shadow-sm">
                        <i class="fas fa-file-alt mr-1.5"></i> CV Builder: {{ $cvOrders->total() }}
                    </span>
                    <span class="px-4 py-2.5 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800/80 text-sm font-bold text-emerald-600 dark:text-emerald-300 shadow-sm">
                        <i class="fas fa-wallet mr-1.5"></i> Isi Saldo: {{ $deposits->total() }}
                    </span>

                    <!-- Auto-Refresh Live Indicator -->
                    <button id="toggle-autorefresh-btn" type="button" onclick="toggleAutoRefresh()" class="px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 text-sm font-bold text-emerald-700 dark:text-emerald-300 shadow-sm flex items-center gap-2 cursor-pointer transition" title="Klik untuk menjeda / melanjutkan auto-refresh">
                        <span id="autorefresh-dot" class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span id="autorefresh-text">Auto-Refresh: ON (<span id="autorefresh-countdown">10</span>s)</span>
                    </button>
                    
                    <!-- Clear All Data Button -->
                    <form action="{{ route('admin.transactions.clear-all') }}" method="POST" onsubmit="return confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS SELURUH riwayat transaksi lama? Data yang dihapus tidak dapat dikembalikan.');" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 rounded-2xl bg-red-50 hover:bg-red-100 dark:bg-red-950/50 dark:hover:bg-red-900/60 border border-red-200 dark:border-red-800 text-sm font-bold text-red-600 dark:text-red-300 shadow-sm transition flex items-center gap-1.5 cursor-pointer" title="Bersihkan Seluruh Transaksi Lama">
                            <i class="fas fa-trash-alt"></i> Bersihkan Riwayat
                        </button>
                    </form>
                </div>
            </div>

            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 font-bold text-sm flex items-center gap-3">
                <i class="fas fa-check-circle text-lg text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif
            @if(session('warning'))
            <div class="p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-800 text-amber-800 dark:text-amber-200 font-bold text-sm flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-lg text-amber-600"></i>
                <span>{{ session('warning') }}</span>
            </div>
            @endif
            @if(session('error'))
            <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950/60 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 font-bold text-sm flex items-center gap-3">
                <i class="fas fa-times-circle text-lg text-red-600"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif
            @if(session('info'))
            <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 font-bold text-sm flex items-center gap-3">
                <i class="fas fa-info-circle text-lg text-blue-600"></i>
                <span>{{ session('info') }}</span>
            </div>
            @endif

            <!-- Tab Switcher -->
            <div class="flex flex-wrap items-center gap-2 bg-white dark:bg-gray-800 p-1.5 rounded-2xl border border-gray-200 dark:border-gray-700 w-fit shadow-sm dark:shadow-lg">
                <button id="admin-tab-all" 
                        type="button"
                        onclick="filterAdminTrxTab('all')" 
                        class="admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-indigo-600 text-white shadow-md cursor-pointer">
                    Semua Kategori
                </button>
                <button id="admin-tab-software" 
                        type="button"
                        onclick="filterAdminTrxTab('software')" 
                        class="admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-desktop text-blue-500"></i> Pembelian Sistem & Software ({{ $orders->total() }})
                </button>
                <button id="admin-tab-topup" 
                        type="button"
                        onclick="filterAdminTrxTab('topup')" 
                        class="admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-gamepad text-purple-500"></i> Transaksi Top Up Game ({{ $transactions->total() }})
                </button>
                <button id="admin-tab-cv" 
                        type="button"
                        onclick="filterAdminTrxTab('cv')" 
                        class="admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-file-alt text-amber-500"></i> Pesanan CV & Resume ({{ $cvOrders->total() }})
                </button>
                <button id="admin-tab-deposit" 
                        type="button"
                        onclick="filterAdminTrxTab('deposit')" 
                        class="admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 cursor-pointer">
                    <i class="fas fa-wallet text-emerald-500"></i> Isi Saldo User ({{ $deposits->total() }})
                </button>
            </div>

            <!-- TAB 1: Pembelian Sistem & Software (POS / Software) -->
            <div id="admin-section-software" class="admin-trx-section bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl overflow-hidden transition-colors duration-200">
                <div class="px-6 py-4.5 border-b border-gray-200 dark:border-gray-700/80 flex items-center justify-between bg-slate-50 dark:bg-gray-800/80">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <i class="fas fa-desktop text-blue-500"></i> Daftar Pembelian Lisensi Sistem & Software
                    </h2>
                    <span class="text-xs text-blue-600 dark:text-blue-400 font-bold bg-blue-500/10 px-3 py-1 rounded-full border border-blue-500/20">
                        Total: {{ $orders->total() }} Transaksi
                    </span>
                </div>

                @if($orders->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-laptop-code text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Belum ada transaksi pembelian sistem atau software.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-gray-900/60 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
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
                                    <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50 transition">
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
            <div id="admin-section-topup" class="admin-trx-section space-y-6">
                <!-- Filter Search & Status -->
                <div class="bg-white dark:bg-gray-800 p-5 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl transition-colors duration-200">
                    <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex flex-wrap items-center gap-4">
                        <div class="flex-1 min-w-[240px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID, Target ID, Provider TRX ID..." 
                                class="w-full bg-slate-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:border-indigo-500">
                        </div>
                        <div>
                            <select name="status" class="bg-slate-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl px-4 py-2.5 text-sm text-gray-900 dark:text-white focus:outline-none focus:border-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid / Success</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition shadow-md shadow-indigo-600/30 cursor-pointer">
                                <i class="fas fa-search mr-1.5"></i> Filter
                            </button>
                            @if(request('search') || request('status'))
                                <a href="{{ route('admin.transactions.index') }}" class="p-2.5 text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                                    <i class="fas fa-undo"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                <!-- Top Up Game Transactions Table -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl overflow-hidden transition-colors duration-200">
                    <div class="px-6 py-4.5 border-b border-gray-200 dark:border-gray-700/80 flex items-center justify-between bg-slate-50 dark:bg-gray-800/80">
                        <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                            <i class="fas fa-gamepad text-purple-500"></i> Transaksi Top Up Game
                        </h2>
                        <span class="text-xs text-purple-600 dark:text-purple-400 font-bold bg-purple-500/10 px-3 py-1 rounded-full border border-purple-500/20">
                            Total: {{ $transactions->total() }} Transaksi
                        </span>
                    </div>

                    @if($transactions->isEmpty())
                        <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-inbox text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                            <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Tidak ada transaksi top up ditemukan.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-gray-900/60 text-gray-500 dark:text-gray-400 text-xs uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-3.5 px-6">No. Invoice</th>
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
                                        <tr class="hover:bg-slate-50 dark:hover:bg-gray-700/50 transition">
                                            <td class="py-4 px-6 font-mono text-xs text-indigo-600 dark:text-indigo-400 font-bold whitespace-nowrap">
                                                {{ $trx->invoice_number ?? $trx->id }}
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
                                                @if($trx->status === 'success')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                        <i class="fas fa-check-circle text-[10px]"></i> Sukses
                                                    </span>
                                                @elseif($trx->status === 'processing' || $trx->status === 'waiting')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20">
                                                        <i class="fas fa-spinner animate-spin text-[10px]"></i> Diproses Provider
                                                    </span>
                                                @elseif($trx->status === 'refunded')
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-500/20">
                                                        <i class="fas fa-undo text-[10px]"></i> Refunded
                                                    </span>
                                                @elseif($trx->status === 'pending' || $trx->status === 'paid')
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
                                                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                                                    @if($trx->status === 'pending' || $trx->status === 'paid')
                                                        <!-- ACC & Auto Fulfillment via API -->
                                                        <form action="{{ route('admin.transactions.approve', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin ACC dan OTOMATIS menembak diamond ke ID {{ $trx->target_field_1 }} via API VIP Reseller?');" class="inline">
                                                            @csrf
                                                            <button type="submit" title="ACC & Kirim Otomatis via API VIP Reseller" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-sm transition cursor-pointer">
                                                                <i class="fas fa-bolt"></i> ACC (API)
                                                            </button>
                                                        </form>

                                                        <!-- Manual Success -->
                                                        <form action="{{ route('admin.transactions.manual-success', $trx->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini Sukses Manual?');" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Tandai Sukses Manual" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-500/10 hover:bg-blue-500/20 border border-blue-500/30 transition cursor-pointer">
                                                                <i class="fas fa-check"></i> Manual
                                                            </button>
                                                        </form>
                                                    @elseif($trx->status === 'processing')
                                                        <!-- Manual Success if finished -->
                                                        <form action="{{ route('admin.transactions.manual-success', $trx->id) }}" method="POST" onsubmit="return confirm('Tandai pesanan ini Sukses Manual?');" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Tandai Sukses Manual" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 transition cursor-pointer">
                                                                <i class="fas fa-check-double"></i> Selesai
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($trx->user_id && $trx->status !== 'refunded')
                                                        <!-- Refund ke Saldo Akun -->
                                                        <form action="{{ route('admin.transactions.refund', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin me-REFUND dana sebesar Rp{{ number_format($trx->amount, 0, ',', '.') }} ke Saldo Akun {{ $trx->user ? $trx->user->name : 'User' }}?');" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Refund ke Saldo Akun User" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-amber-700 dark:text-amber-300 bg-amber-100 dark:bg-amber-950/80 hover:bg-amber-200 dark:hover:bg-amber-900 border border-amber-300 dark:border-amber-700 transition cursor-pointer">
                                                                <i class="fas fa-undo"></i> Refund Saldo
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($trx->status !== 'refunded' && $trx->status !== 'failed')
                                                        <!-- Reject / Batalkan -->
                                                        <form action="{{ route('admin.transactions.reject', $trx->id) }}" method="POST" onsubmit="return confirm('Tolak/Batalkan pesanan ini?');" class="inline">
                                                            @csrf
                                                            <button type="submit" title="Tolak Pesanan" class="inline-flex items-center gap-1 px-2 py-1.5 rounded-xl text-xs font-bold text-red-600 dark:text-red-400 bg-red-500/10 hover:bg-red-500/20 border border-red-500/30 transition cursor-pointer">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <a href="{{ route('admin.transactions.invoice', $trx->id) }}" target="_blank" class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition shadow-sm" title="Lihat Invoice">
                                                        <i class="fas fa-file-invoice"></i> Invoice
                                                    </a>

                                                    <!-- Delete Row -->
                                                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Hapus permanen riwayat transaksi ini?');" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" title="Hapus Transaksi" class="inline-flex items-center p-1.5 rounded-xl text-xs text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/50 transition cursor-pointer">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
                                                    </form>
                                                </div>
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

            <!-- TAB 3: Pesanan CV & Resume Builder -->
            <div id="admin-section-cv" class="admin-trx-section bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl overflow-hidden transition-colors duration-200">
                <div class="px-6 py-4.5 border-b border-gray-200 dark:border-gray-700/80 flex items-center justify-between bg-slate-50 dark:bg-gray-800/80">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <i class="fas fa-file-alt text-amber-500"></i> Daftar Pesanan Pembuatan CV & Resume Builder
                    </h2>
                    <span class="text-xs text-amber-600 dark:text-amber-400 font-bold bg-amber-500/10 px-3 py-1 rounded-full border border-amber-500/20">
                        Total: {{ $cvOrders->total() }} Pesanan
                    </span>
                </div>

                @if($cvOrders->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-file-invoice text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Belum ada pesanan pembuatan CV yang masuk.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-700/80 text-[11px] font-extrabold uppercase tracking-wider text-gray-500 dark:text-gray-400 bg-gray-50/50 dark:bg-gray-800/50">
                                    <th class="py-4 px-6">Invoice</th>
                                    <th class="py-4 px-6">Pemesan & Kontak</th>
                                    <th class="py-4 px-6">Template CV</th>
                                    <th class="py-4 px-6">Tagihan</th>
                                    <th class="py-4 px-6">Tanggal</th>
                                    <th class="py-4 px-6 text-center">Status</th>
                                    <th class="py-4 px-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60 text-sm">
                                @foreach($cvOrders as $cvItem)
                                    <tr class="hover:bg-gray-50/80 dark:hover:bg-gray-700/40 transition">
                                        <td class="py-4 px-6 font-mono font-bold text-gray-900 dark:text-white">
                                            {{ $cvItem->invoice_number ?? ('INV/CV/' . $cvItem->id) }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $cvItem->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $cvItem->email }}</div>
                                            @if(!empty($cvItem->phone))
                                            <div class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ $cvItem->phone }}</div>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 font-bold text-xs border border-amber-200 dark:border-amber-800">
                                                <i class="fas fa-file-alt"></i> {{ $cvItem->template_name }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 font-black text-gray-900 dark:text-white">
                                            Rp{{ number_format($cvItem->price, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($cvItem->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                        </td>
                                        <td class="py-4 px-6 text-center">
                                            @if($cvItem->status === 'PAID')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 rounded-full text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Diterima / Paid
                                                </span>
                                            @elseif($cvItem->status === 'FAILED')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 dark:bg-red-950/60 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 rounded-full text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Ditolak
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 rounded-full text-xs font-bold animate-pulse">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu ACC
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($cvItem->status === 'PENDING')
                                                    <!-- ACC Button (Direct & Form compatible) -->
                                                    <a href="{{ route('admin.transactions.cv.approve', $cvItem->id) }}" onclick="if(typeof autoRefreshEnabled !== 'undefined') autoRefreshEnabled = false; return confirm('ACC pembayaran CV ini? Link download PDF akan langsung aktif untuk pengguna.');" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 cursor-pointer">
                                                        <i class="fas fa-check"></i> ACC Bayar
                                                    </a>
                                                    <!-- Reject Button -->
                                                    <a href="{{ route('admin.transactions.cv.reject', $cvItem->id) }}" onclick="if(typeof autoRefreshEnabled !== 'undefined') autoRefreshEnabled = false; return confirm('Tolak pesanan CV ini?');" class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-sm flex items-center gap-1.5 cursor-pointer">
                                                        <i class="fas fa-times"></i> Tolak
                                                    </a>
                                                @endif

                                                @if($cvItem->status === 'PAID')
                                                    <!-- Download PDF for Admin -->
                                                    <a href="{{ route('cv.download', $cvItem->access_token ?? $cvItem->id) }}" target="_blank" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm flex items-center gap-1.5">
                                                        <i class="fas fa-download"></i> PDF
                                                    </a>
                                                @endif

                                                <!-- Invoice / Details Link -->
                                                <a href="{{ route('cv.checkout.show', $cvItem->access_token ?? $cvItem->id) }}" target="_blank" class="p-2 rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-bold text-xs" title="Lihat Halaman Checkout / Invoice">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.transactions.cv.destroy', $cvItem->id) }}" method="POST" onsubmit="return confirm('Hapus data pesanan CV ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-xl bg-red-50 hover:bg-red-100 dark:bg-red-950/40 text-red-600 dark:text-red-400 font-bold text-xs cursor-pointer" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($cvOrders->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $cvOrders->links() }}
                        </div>
                    @endif
                @endif
            </div>

            <!-- TAB 4: Isi Saldo User (Deposits) -->
            <div id="admin-section-deposit" class="admin-trx-section bg-white dark:bg-gray-800 rounded-3xl border border-gray-200 dark:border-gray-700 shadow-sm dark:shadow-2xl overflow-hidden transition-colors duration-200">
                <div class="px-6 py-4.5 border-b border-gray-200 dark:border-gray-700/80 flex items-center justify-between bg-slate-50 dark:bg-gray-800/80">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2.5">
                        <i class="fas fa-wallet text-emerald-500"></i> Daftar Permintaan Isi Saldo (Deposit) User
                    </h2>
                    <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold bg-emerald-500/10 px-3 py-1 rounded-full border border-emerald-500/20">
                        Total: {{ $deposits->total() }} Transaksi
                    </span>
                </div>

                @if($deposits->isEmpty())
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-wallet text-5xl mb-3 text-gray-300 dark:text-gray-600"></i>
                        <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Belum ada transaksi pengisian saldo akun.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 dark:bg-gray-800/80 text-gray-500 dark:text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="py-4 px-6">ID Deposit</th>
                                    <th class="py-4 px-6">Pengguna</th>
                                    <th class="py-4 px-6">Nominal Saldo</th>
                                    <th class="py-4 px-6">Metode</th>
                                    <th class="py-4 px-6">Status</th>
                                    <th class="py-4 px-6">Waktu</th>
                                    <th class="py-4 px-6 text-right">Aksi Super Admin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700/60 font-medium">
                                @foreach($deposits as $dep)
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-gray-700/40 transition-colors">
                                        <td class="py-4 px-6 font-mono font-bold text-gray-900 dark:text-white">
                                            {{ $dep->id }}
                                        </td>
                                        <td class="py-4 px-6">
                                            <div class="font-bold text-gray-900 dark:text-white">{{ $dep->user->name ?? 'User #' . $dep->user_id }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $dep->user->email ?? '-' }}</div>
                                        </td>
                                        <td class="py-4 px-6 font-black text-emerald-600 dark:text-emerald-400 font-mono text-base">
                                            Rp{{ number_format($dep->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="py-4 px-6 uppercase font-bold text-xs text-gray-600 dark:text-gray-300">
                                            {{ $dep->payment_method }}
                                        </td>
                                        <td class="py-4 px-6">
                                            @if($dep->status === 'success' || $dep->status === 'paid')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20">
                                                    <i class="fas fa-check-circle text-[10px]"></i> Sukses Masuk
                                                </span>
                                            @elseif($dep->status === 'pending')
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20">
                                                    <i class="fas fa-clock text-[10px] animate-spin"></i> Menunggu Bayar
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-gray-500/10 text-gray-600 dark:text-gray-400 border border-gray-500/20">
                                                    {{ ucfirst($dep->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-6 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $dep->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                @if($dep->status === 'pending')
                                                    <!-- ACC Deposit Button -->
                                                    <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST" onsubmit="return confirm('Setujui permintaan deposit ini? Saldo akun pengguna akan otomatis bertambah.');">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-md transition cursor-pointer" title="ACC Saldo Masuk">
                                                            <i class="fas fa-check text-xs"></i> ACC Deposit
                                                        </button>
                                                    </form>

                                                    <!-- Batalkan Button -->
                                                    <form action="{{ route('admin.deposits.cancel', $dep->id) }}" method="POST" onsubmit="return confirm('Batalkan permintaan deposit ini?');">
                                                        @csrf
                                                        <button type="submit" class="p-2 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 text-amber-600 dark:text-amber-400 border border-amber-500/20 font-bold text-xs transition cursor-pointer" title="Batalkan">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <!-- Delete Button -->
                                                <form action="{{ route('admin.deposits.destroy', $dep->id) }}" method="POST" onsubmit="return confirm('Hapus data deposit ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-600 dark:text-red-400 border border-red-500/20 font-bold text-xs transition cursor-pointer" title="Hapus Data">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($deposits->hasPages())
                        <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $deposits->links() }}
                        </div>
                    @endif
                @endif
            </div>

        </div>
    </div>

    <script>
        // 1. Tab Switcher with LocalStorage Memory
        function filterAdminTrxTab(tabName) {
            localStorage.setItem('totap_admin_trx_tab', tabName);
            const sections = document.querySelectorAll('.admin-trx-section');
            const allBtns = document.querySelectorAll('.admin-tab-btn');
            
            allBtns.forEach(btn => {
                btn.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white flex items-center gap-2 cursor-pointer';
            });

            const secSoft = document.getElementById('admin-section-software');
            const secTop = document.getElementById('admin-section-topup');
            const secCv = document.getElementById('admin-section-cv');
            const secDep = document.getElementById('admin-section-deposit');

            if (tabName === 'all') {
                sections.forEach(s => s.classList.remove('hidden'));
                const b = document.getElementById('admin-tab-all');
                if (b) b.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-indigo-600 text-white shadow-md cursor-pointer';
            } else if (tabName === 'software') {
                if (secSoft) secSoft.classList.remove('hidden');
                if (secTop) secTop.classList.add('hidden');
                if (secCv) secCv.classList.add('hidden');
                if (secDep) secDep.classList.add('hidden');
                const b = document.getElementById('admin-tab-software');
                if (b) b.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-blue-600 text-white shadow-md flex items-center gap-2 cursor-pointer';
            } else if (tabName === 'topup') {
                if (secSoft) secSoft.classList.add('hidden');
                if (secTop) secTop.classList.remove('hidden');
                if (secCv) secCv.classList.add('hidden');
                if (secDep) secDep.classList.add('hidden');
                const b = document.getElementById('admin-tab-topup');
                if (b) b.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-purple-600 text-white shadow-md flex items-center gap-2 cursor-pointer';
            } else if (tabName === 'cv') {
                if (secSoft) secSoft.classList.add('hidden');
                if (secTop) secTop.classList.add('hidden');
                if (secCv) secCv.classList.remove('hidden');
                if (secDep) secDep.classList.add('hidden');
                const b = document.getElementById('admin-tab-cv');
                if (b) b.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-amber-600 text-white shadow-md flex items-center gap-2 cursor-pointer';
            } else if (tabName === 'deposit') {
                if (secSoft) secSoft.classList.add('hidden');
                if (secTop) secTop.classList.add('hidden');
                if (secCv) secCv.classList.add('hidden');
                if (secDep) secDep.classList.remove('hidden');
                const b = document.getElementById('admin-tab-deposit');
                if (b) b.className = 'admin-tab-btn px-5 py-2.5 rounded-xl font-bold text-sm transition bg-emerald-600 text-white shadow-md flex items-center gap-2 cursor-pointer';
            }
        }

        // Restore active tab on load
        document.addEventListener('DOMContentLoaded', () => {
            const savedTab = localStorage.getItem('totap_admin_trx_tab') || 'all';
            filterAdminTrxTab(savedTab);
        });

        // 2. Real-Time Auto-Refresh Engine (10 Seconds Interval)
        let autoRefreshEnabled = true;
        let countdown = 10;
        const countdownEl = document.getElementById('autorefresh-countdown');
        const textEl = document.getElementById('autorefresh-text');
        const dotEl = document.getElementById('autorefresh-dot');
        const btnEl = document.getElementById('toggle-autorefresh-btn');

        function updateBadge() {
            if (autoRefreshEnabled) {
                if (countdownEl) countdownEl.innerText = countdown;
                if (textEl) textEl.innerHTML = `Auto-Refresh: ON (<span id="autorefresh-countdown">${countdown}</span>s)`;
                if (dotEl) dotEl.className = 'w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse';
                if (btnEl) btnEl.className = 'px-4 py-2.5 rounded-2xl bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/60 dark:hover:bg-emerald-900/60 border border-emerald-200 dark:border-emerald-800 text-sm font-bold text-emerald-700 dark:text-emerald-300 shadow-sm flex items-center gap-2 cursor-pointer transition';
            } else {
                if (textEl) textEl.innerText = 'Auto-Refresh: PAUSED';
                if (dotEl) dotEl.className = 'w-2.5 h-2.5 rounded-full bg-gray-400';
                if (btnEl) btnEl.className = 'px-4 py-2.5 rounded-2xl bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 text-sm font-bold text-gray-500 dark:text-gray-400 shadow-sm flex items-center gap-2 cursor-pointer transition';
            }
        }

        function toggleAutoRefresh() {
            autoRefreshEnabled = !autoRefreshEnabled;
            countdown = 10;
            updateBadge();
        }

        setInterval(() => {
            if (!autoRefreshEnabled) return;
            
            // Check if user is actively typing in a search/filter input
            const activeInput = document.activeElement;
            if (activeInput && (activeInput.tagName === 'INPUT' || activeInput.tagName === 'TEXTAREA')) {
                return; // Pause reload while typing
            }

            countdown--;
            if (countdown <= 0) {
                window.location.reload();
            } else {
                updateBadge();
            }
        }, 1000);
    </script>
</x-app-layout>
