<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @php
                $totalUsers = \App\Models\User::where('role', 'customer')->count();
                $totalProducts = \App\Models\Product::count();
                $totalRevenue = \App\Models\Payment::where('status', 'PAID')->sum('amount');
                $activeSubscriptions = \App\Models\Subscription::where('status', 'ACTIVE')->count();

                // Real customer reviews and feedback
                $totalReviews = \App\Models\CustomerReview::count();
                $avgRating = $totalReviews > 0 ? round(\App\Models\CustomerReview::avg('rating'), 1) : 5.0;
                $recentFeedbacks = \App\Models\CustomerReview::latest()->take(6)->get();
                $star5 = \App\Models\CustomerReview::where('rating', 5)->count();
                $star4 = \App\Models\CustomerReview::where('rating', 4)->count();
                $star3 = \App\Models\CustomerReview::where('rating', 3)->count();
                $star2 = \App\Models\CustomerReview::where('rating', 2)->count();
                $star1 = \App\Models\CustomerReview::where('rating', 1)->count();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Customer</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Produk</h3>
                    <p class="text-2xl font-black text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Langganan Aktif</h3>
                    <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $activeSubscriptions }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-gray-100 dark:border-gray-700">
                    <h3 class="text-gray-500 dark:text-gray-400 text-xs font-semibold uppercase tracking-wider mb-1">Total Pendapatan</h3>
                    <p class="text-2xl font-black text-indigo-600 dark:text-indigo-400">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-amber-500/10 dark:from-amber-950/40 dark:to-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-5 border border-amber-300/40 dark:border-amber-700/40">
                    <h3 class="text-amber-600 dark:text-amber-400 text-xs font-bold uppercase tracking-wider mb-1">⭐ Rating Website</h3>
                    <div class="flex items-baseline gap-1.5">
                        <span class="text-2xl font-black text-amber-500">{{ number_format($avgRating, 1) }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold">/ 5.0</span>
                    </div>
                    <span class="text-[11px] text-gray-500 dark:text-gray-400 block mt-0.5">{{ $totalReviews }} ulasan pembeli</span>
                </div>
            </div>

            <!-- SECTION: Rating & Kritik/Saran Khusus Super Admin -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Star Breakdown & Satisfaction Overview -->
                <div class="lg:col-span-4 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>⭐</span> Rating Kepuasan Website
                            </h3>
                            <a href="{{ route('admin.reviews.index') }}" class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                                Detail →
                            </a>
                        </div>
                        
                        <div class="text-center py-4 bg-gray-50 dark:bg-gray-900/60 rounded-xl mb-5 border border-gray-100 dark:border-gray-700/60">
                            <div class="text-4xl font-black text-amber-400 font-mono">{{ number_format($avgRating, 1) }}</div>
                            <div class="flex justify-center text-amber-400 text-base my-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($avgRating) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Rata-rata dari <strong>{{ $totalReviews }}</strong> pembeli terverifikasi</p>
                        </div>

                        <div class="space-y-2 text-xs">
                            @foreach([5 => $star5, 4 => $star4, 3 => $star3, 2 => $star2, 1 => $star1] as $s => $cnt)
                                @php
                                    $pct = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span class="w-8 font-bold text-gray-700 dark:text-gray-300">{{ $s }} ★</span>
                                    <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-700 rounded-full overflow-hidden">
                                        <div class="h-full bg-amber-400 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="w-10 text-right font-mono text-gray-400">{{ $cnt }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-100 dark:border-gray-700/60 text-center">
                        <span class="text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold bg-emerald-50 dark:bg-emerald-950/60 px-3 py-1 rounded-full border border-emerald-200 dark:border-emerald-800">
                            ✓ Terintegrasi Langsung Pasca-Pembayaran
                        </span>
                    </div>
                </div>

                <!-- Latest Criticisms & Suggestions Feed (Super Admin Exclusive) -->
                <div class="lg:col-span-8 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-100 dark:border-gray-700">
                        <div>
                            <h3 class="text-base font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span>🔒</span> Kritik & Saran Khusus Super Admin
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Hanya dapat dilihat oleh Super Admin untuk perbaikan & evaluasi performa sistem.</p>
                        </div>
                        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-1.5 bg-indigo-50 dark:bg-indigo-950/60 hover:bg-indigo-100 dark:hover:bg-indigo-900/60 text-indigo-600 dark:text-indigo-400 text-xs font-bold rounded-xl transition">
                            Lihat Semua Feedback ({{ $totalReviews }}) →
                        </a>
                    </div>

                    <div class="space-y-3">
                        @forelse($recentFeedbacks as $feed)
                            <div class="p-3.5 bg-gray-50 dark:bg-gray-900/60 rounded-xl border border-gray-100 dark:border-gray-700/70 hover:border-indigo-300 dark:hover:border-indigo-700 transition">
                                <div class="flex items-start justify-between gap-3 mb-1.5">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-bold text-xs text-gray-900 dark:text-white">{{ $feed->customer_name }}</span>
                                        @if($feed->customer_contact)
                                            <span class="text-[11px] text-gray-400">({{ $feed->customer_contact }})</span>
                                        @endif
                                        <span class="text-[10px] uppercase font-mono px-2 py-0.5 rounded font-bold {{ $feed->order_type === 'cv' ? 'bg-purple-100 dark:bg-purple-950 text-purple-700 dark:text-purple-300' : ($feed->order_type === 'software' ? 'bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300' : 'bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300') }}">
                                            {{ $feed->order_type }}
                                        </span>
                                    </div>
                                    <div class="flex items-center text-amber-400 text-xs font-bold font-mono whitespace-nowrap">
                                        <span>{{ str_repeat('★', $feed->rating) }}{{ str_repeat('☆', 5 - $feed->rating) }}</span>
                                        <span class="ml-1 text-gray-500">({{ $feed->rating }}/5)</span>
                                    </div>
                                </div>

                                @if($feed->review_text)
                                    <p class="text-xs text-gray-800 dark:text-gray-200 leading-relaxed font-sans bg-white dark:bg-gray-800 p-2.5 rounded-lg border border-gray-200/60 dark:border-gray-700/60">
                                        "{{ $feed->review_text }}"
                                    </p>
                                @else
                                    <span class="text-[11px] text-gray-400 italic">Pelanggan hanya memberikan rating bintang tanpa catatan saran.</span>
                                @endif

                                <div class="flex items-center justify-between text-[10px] text-gray-400 mt-2">
                                    <span>Produk: <strong class="text-gray-600 dark:text-gray-300">{{ $feed->product_name ?? 'Layanan' }}</strong></span>
                                    <span>{{ $feed->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 text-xs">
                                Belum ada kritik atau saran yang masuk dari pembeli.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Manage Subscriptions/Customers -->
            <div class="flex justify-between items-center bg-white dark:bg-gray-800 p-6 sm:rounded-lg shadow-sm">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Registrasi Manual Pelanggan</h3>
                    <p class="text-sm text-gray-500">Buat akun admin secara manual jika pelanggan melakukan pembelian di luar sistem (misal transfer langsung).</p>
                </div>
                <a href="{{ route('admin.customers.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                    + Tambah Pelanggan / Admin Baru
                </a>
            </div>

            <!-- Recent Orders -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Pesanan Terbaru</h3>
                    @php
                        $recentOrders = \App\Models\Order::with(['user', 'product'])->latest()->take(5)->get();
                    @endphp

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Order ID</th>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">Produk</th>
                                <th class="border-b py-2 px-4">Paket</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="border-b py-2 px-4 text-sm font-mono">{{ $order->order_number }}</td>
                                    <td class="border-b py-2 px-4">{{ $order->user->name }}</td>
                                    <td class="border-b py-2 px-4">{{ $order->product->name }}</td>
                                    <td class="border-b py-2 px-4">
                                        @if($order->plan)
                                            <span style="display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; background:{{ strtolower($order->plan->name) === 'pro' ? '#FEF3C7' : '#DBEAFE' }}; color:{{ strtolower($order->plan->name) === 'pro' ? '#92400E' : '#1D4ED8' }};">
                                                {{ strtoupper($order->plan->name) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Manage Subscriptions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Daftar Pelanggan & Langganan (Sisa Durasi)</h3>
                    @php
                        $subs = \App\Models\Subscription::with(['user', 'product', 'plan'])->orderBy('end_date', 'desc')->get();
                    @endphp

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b py-2 px-4">Customer</th>
                                <th class="border-b py-2 px-4">Produk</th>
                                  <th class="border-b py-2 px-4">Paket</th>
                                  <th class="border-b py-2 px-4">Kasir / Pegawai</th>
                                <th class="border-b py-2 px-4">Sisa Durasi</th>
                                <th class="border-b py-2 px-4">Status</th>
                                <th class="border-b py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subs as $sub)
                                <tr>
                                    <td class="border-b py-2 px-4 font-medium">
                                        {{ $sub->user->name }}
                                        <div class="text-xs text-gray-500">{{ $sub->user->email }}</div>
                                    </td>
                                    <td class="border-b py-2 px-4">{{ $sub->product->name }}</td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->plan)
                                              <span style="display:inline-block; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:700; background:{{ strtolower($sub->plan->name) === 'pro' ? '#FEF3C7' : '#DBEAFE' }}; color:{{ strtolower($sub->plan->name) === 'pro' ? '#92400E' : '#1D4ED8' }};">
                                                  {{ strtoupper($sub->plan->name) }}
                                              </span>
                                          @else
                                              <span class="text-gray-400 text-xs">-</span>
                                          @endif
                                      </td>
                                      <td class="border-b py-2 px-4">
                                          @if($sub->product && $sub->product->slug === 'sistem-kasir-pos')
                                              @php
                                                  $kasirUser = DB::connection('kasir')->table('users')->where('email', $sub->user->email)->first();
                                                  $kasirEmployees = $kasirUser ? DB::connection('kasir')->table('users')
                                                      ->where('store_id', $kasirUser->store_id)
                                                      ->where('role', 'cashier')
                                                      ->get(['name','email']) : collect();
                                              @endphp
                                              @if($kasirEmployees->count() > 0)
                                                  @foreach($kasirEmployees as $emp)
                                                      <div class="text-xs text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ $emp->name }}</span> — {{ $emp->email }}</div>
                                                  @endforeach
                                              @else
                                                  <span class="text-xs text-gray-400 italic">Belum ada kasir</span>
                                              @endif
                                          @else
                                              <span class="text-xs text-gray-400">-</span>
                                          @endif
                                      </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->end_date && \Carbon\Carbon::parse($sub->end_date)->isFuture())
                                            <span class="text-green-600 font-semibold">{{ abs((int) \Carbon\Carbon::parse($sub->end_date)->diffInDays(now())) }} Hari Lagi</span>
                                        @else
                                            <span class="text-red-600 font-semibold">Expired</span>
                                        @endif
                                    </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->status === 'ACTIVE' || $sub->status === 'active')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                        @elseif($sub->status === 'revoked')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Dicabut</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ $sub->status }}</span>
                                        @endif
                                    </td>
                                    <td class="border-b py-2 px-4">
                                        @if($sub->status === 'ACTIVE' || $sub->status === 'active')
                                            <form action="{{ route('admin.customers.revoke', $sub->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin mencabut akses aplikasi untuk pelanggan ini?');">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-semibold transition">
                                                    Cabut Akses (Kick)
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Registered Customers List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-bold mb-4">Daftar Akun Klien Terdaftar</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-800 border-collapse">
                            <thead>
                                <tr class="text-left text-gray-500 border-b">
                                    <th class="py-2 px-4">Nama / Organisasi</th>
                                    <th class="py-2 px-4">Email</th>
                                    <th class="py-2 px-4">No. WhatsApp</th>
                                    <th class="py-2 px-4">Role</th>
                                    <th class="py-2 px-4">Password</th>
                                    <th class="py-2 px-4">Terdaftar Pada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allUsers = \App\Models\User::orderBy('created_at', 'desc')->get();
                                @endphp
                                @forelse($allUsers as $user)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        <td class="border-b py-2 px-4 font-semibold">{{ $user->name }}</td>
                                        <td class="border-b py-2 px-4">{{ $user->email }}</td>
                                        <td class="border-b py-2 px-4">{{ $user->phone_number ?? '-' }}</td>
                                        <td class="border-b py-2 px-4">
                                            @if($user->role === 'superadmin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Super Admin</span>
                                            @elseif($user->role === 'admin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Admin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Customer</span>
                                            @endif
                                        </td>
                                        <td class="border-b py-2 px-4">
                                            <span class="text-xs text-gray-400 font-mono tracking-widest cursor-help" title="Password dienkripsi (Bcrypt) demi keamanan standar internasional. Admin tidak dapat melihatnya.">********</span>
                                        </td>
                                        <td class="border-b py-2 px-4 text-sm text-gray-500">{{ $user->created_at->format('d M Y, H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="border-b py-4 px-4 text-center text-gray-500">Belum ada user yang terdaftar.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
