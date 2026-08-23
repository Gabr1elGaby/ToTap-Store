<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    ⭐ {{ __('Ulasan & Feedback Pelanggan') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Daftar seluruh rating bintang, kritik, dan saran dari pelanggan setelah menyelesaikan transaksi.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-400 text-xs font-bold">
                    Rata-Rata: ⭐ {{ number_format($avgRating, 1) }} / 5.0
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/20 border border-emerald-500/40 text-emerald-300 rounded-xl text-sm font-semibold">
                    ✓ {{ session('success') }}
                </div>
            @endif

            {{-- Summary Stats Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Overall Average Card --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-amber-500/20 border border-amber-400 flex items-center justify-center text-3xl text-amber-400">
                        ⭐
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Rating Kepuasan</span>
                        <div class="text-3xl font-black text-gray-900 dark:text-white font-mono mt-0.5">
                            {{ number_format($avgRating, 1) }} <span class="text-sm font-normal text-gray-400">/ 5.0</span>
                        </div>
                        <span class="text-xs text-emerald-500 font-semibold">Berdasarkan {{ number_format($totalReviews) }} ulasan pembeli</span>
                    </div>
                </div>

                {{-- Total Feedback Card --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-blue-500/20 border border-blue-400 flex items-center justify-center text-3xl text-blue-400">
                        💬
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider font-semibold">Total Ulasan Masuk</span>
                        <div class="text-3xl font-black text-gray-900 dark:text-white font-mono mt-0.5">
                            {{ number_format($totalReviews) }}
                        </div>
                        <span class="text-xs text-gray-400">Verified buyers</span>
                    </div>
                </div>

                {{-- Star Breakdown --}}
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-1.5 text-xs">
                    @for($s = 5; $s >= 1; $s--)
                        @php
                            $cnt = $ratingCounts[$s] ?? 0;
                            $pct = $totalReviews > 0 ? round(($cnt / $totalReviews) * 100) : 0;
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="w-10 font-bold text-gray-700 dark:text-gray-300">{{ $s }} ⭐</span>
                            <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="w-8 text-right font-mono text-gray-500 dark:text-gray-400">{{ $cnt }}</span>
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Filter & Search Form --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <form method="GET" action="{{ route('admin.reviews.index') }}" class="flex flex-wrap items-center gap-3">
                    <div class="flex-1 min-w-[240px]">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama, produk, kontak, atau kata kunci saran..." class="w-full text-xs px-3.5 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:ring-blue-500">
                    </div>

                    <div>
                        <select name="rating" class="text-xs px-3 py-2 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                            <option value="">Semua Bintang</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Bintang ⭐⭐⭐⭐⭐</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Bintang ⭐⭐⭐⭐</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Bintang ⭐⭐⭐</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Bintang ⭐⭐</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Bintang ⭐</option>
                        </select>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-bold transition shadow">
                        Filter
                    </button>

                    @if(request()->filled('q') || request()->filled('rating'))
                        <a href="{{ route('admin.reviews.index') }}" class="px-3 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-semibold">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Reviews List Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Daftar Kritik & Saran Pelanggan</h3>
                    <span class="text-xs text-gray-500 dark:text-gray-400">Menampilkan {{ $reviews->count() }} dari {{ $reviews->total() }} ulasan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 uppercase tracking-wider font-semibold border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="py-3 px-4">Tanggal</th>
                                <th class="py-3 px-4">Pelanggan</th>
                                <th class="py-3 px-4">Produk / Layanan</th>
                                <th class="py-3 px-4">Rating</th>
                                <th class="py-3 px-4">Kritik & Saran</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($reviews as $rev)
                                <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                    <td class="py-3 px-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $rev->created_at->format('d M Y, H:i') }}
                                        @if($rev->order_id)
                                            <span class="block text-[10px] font-mono text-gray-400">ID: {{ $rev->order_id }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $rev->customer_name }}</div>
                                        @if($rev->customer_contact)
                                            <div class="text-[11px] text-gray-400">{{ $rev->customer_contact }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-medium text-gray-900 dark:text-gray-200">{{ $rev->product_name }}</span>
                                        <span class="block text-[10px] uppercase font-mono px-1.5 py-0.5 rounded w-max mt-0.5 {{ $rev->order_type === 'software' ? 'bg-indigo-500/10 text-indigo-400' : 'bg-blue-500/10 text-blue-400' }}">
                                            {{ $rev->order_type }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <div class="flex items-center text-amber-400 text-sm">
                                            @for($i = 1; $i <= 5; $i++)
                                                <span>{{ $i <= $rev->rating ? '★' : '☆' }}</span>
                                            @endfor
                                            <span class="ml-1 text-xs font-bold text-gray-700 dark:text-gray-300 font-mono">({{ $rev->rating }}/5)</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 max-w-md">
                                        @if($rev->review_text)
                                            <p class="text-gray-800 dark:text-gray-200 leading-relaxed font-sans bg-gray-50 dark:bg-gray-900/60 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700/60">
                                                "{{ $rev->review_text }}"
                                            </p>
                                        @else
                                            <span class="italic text-gray-400 text-[11px]">Hanya memberikan rating bintang.</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <form action="{{ route('admin.reviews.destroy', $rev) }}" method="POST" class="inline" onsubmit="return confirm('Hapus ulasan ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-rose-500 hover:text-rose-400 font-bold p-1">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        Belum ada ulasan atau feedback pelanggan yang cocok dengan filter.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($reviews->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $reviews->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
