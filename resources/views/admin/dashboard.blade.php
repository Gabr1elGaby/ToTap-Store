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
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Customer</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Produk</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $totalProducts }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-semibold mb-2">Langganan Aktif</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ $activeSubscriptions }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-gray-500 text-sm font-semibold mb-2">Total Pendapatan</h3>
                    <p class="text-3xl font-bold text-indigo-600">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
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
