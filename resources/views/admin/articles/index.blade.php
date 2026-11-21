<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span class="text-emerald-500">📝</span> {{ __('Kelola Artikel SEO & Panduan') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Daftar artikel pendorong SEO ToTap Store (di-hide dari navbar utama, terindeks otomatis oleh Google Search Console).
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('articles.index') }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center gap-1.5 border border-gray-300 dark:border-gray-700">
                    <i class="fas fa-external-link-alt text-[10px]"></i> Lihat Halaman Artikel Publik
                </a>
                <a href="{{ route('admin.articles.create') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:opacity-90 shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                    <i class="fas fa-plus text-xs"></i> Tulis Artikel Baru
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 rounded-xl text-sm font-semibold flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            {{-- Summary Stats Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-xl text-indigo-400">
                        📚
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Total</span>
                        <div class="text-2xl font-black text-gray-900 dark:text-white font-mono mt-0.5">
                            {{ $totalArticles }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-xl text-emerald-400">
                        🚀
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Published</span>
                        <div class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-mono mt-0.5">
                            {{ $publishedCount }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/10 border border-blue-500/30 flex items-center justify-center text-xl text-blue-400">
                        ⏰
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Terjadwal</span>
                        <div class="text-2xl font-black text-blue-500 font-mono mt-0.5">
                            {{ $scheduledCount ?? 0 }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-xl text-amber-400">
                        📝
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Draft</span>
                        <div class="text-2xl font-black text-amber-500 font-mono mt-0.5">
                            {{ $draftCount }}
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-xl text-cyan-400">
                        👁️
                    </div>
                    <div>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-semibold uppercase tracking-wider">Views</span>
                        <div class="text-2xl font-black text-cyan-600 dark:text-cyan-400 font-mono mt-0.5">
                            {{ number_format($totalViews) }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters & Search --}}
            <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <form action="{{ route('admin.articles.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul artikel, kategori, atau penulis..."
                            class="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500">
                        <i class="fas fa-search absolute left-3.5 top-3 text-gray-400 text-xs"></i>
                    </div>

                    <select name="category" class="py-2 px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="all">Semua Kategori</option>
                        <option value="Panduan & Tips" {{ request('category') === 'Panduan & Tips' ? 'selected' : '' }}>Panduan & Tips</option>
                        <option value="Tips Game" {{ request('category') === 'Tips Game' ? 'selected' : '' }}>Tips Game</option>
                        <option value="Review & Keamanan" {{ request('category') === 'Review & Keamanan' ? 'selected' : '' }}>Review & Keamanan</option>
                        <option value="Promo" {{ request('category') === 'Promo' ? 'selected' : '' }}>Promo</option>
                        <option value="Berita" {{ request('category') === 'Berita' ? 'selected' : '' }}>Berita</option>
                    </select>

                    <select name="status" class="py-2 px-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <option value="">Semua Status</option>
                        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published (Live)</option>
                        <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Terjadwal</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    </select>

                    <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['q', 'category', 'status']))
                        <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 text-gray-700 dark:text-gray-300 font-bold rounded-xl text-xs transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Articles Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-gray-50 dark:bg-gray-900/50 text-gray-500 dark:text-gray-400 font-bold uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                            <tr>
                                <th class="py-3 px-4">Artikel</th>
                                <th class="py-3 px-4">Kategori</th>
                                <th class="py-3 px-4 text-center">Views</th>
                                <th class="py-3 px-4 text-center">Status</th>
                                <th class="py-3 px-4">Tanggal Rilis</th>
                                <th class="py-3 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60 text-gray-700 dark:text-gray-300">
                            @forelse($articles as $article)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition">
                                    <td class="py-3 px-4 max-w-sm">
                                        <div class="flex items-center gap-3">
                                            @if($article->thumbnail)
                                                <img src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-12 h-12 object-cover rounded-lg shrink-0 border border-gray-200 dark:border-gray-700">
                                            @else
                                                <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-900 flex items-center justify-center text-lg shrink-0 border border-gray-200 dark:border-gray-700">
                                                    📄
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <a href="{{ route('admin.articles.edit', $article) }}" class="font-bold text-gray-900 dark:text-white hover:text-emerald-500 truncate block text-sm">
                                                    {{ $article->title }}
                                                </a>
                                                <span class="text-[11px] text-gray-400 font-mono truncate block">
                                                    /artikel/{{ $article->slug }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                                            {{ $article->category }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 text-center font-mono font-bold whitespace-nowrap">
                                        {{ number_format($article->views) }}
                                    </td>
                                    <td class="py-3 px-4 text-center whitespace-nowrap">
                                        @if($article->is_published)
                                            @if($article->published_at && $article->published_at->isFuture())
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20" title="Jadwal Tayang: {{ $article->published_at->format('d M Y, H:i') }} WIB">
                                                    ⏰ Terjadwal
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
                                                    ✓ Published
                                                </span>
                                            @endif
                                        @else
                                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 text-amber-400 border border-amber-500/20">
                                                Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        @if($article->published_at && $article->published_at->isFuture())
                                            <span class="text-blue-400 font-medium flex items-center gap-1">
                                                <i class="far fa-clock text-[10px]"></i> {{ $article->published_at->format('d M Y, H:i') }} WIB
                                            </span>
                                        @else
                                            {{ $article->published_at ? $article->published_at->format('d M Y, H:i') : '-' }}
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            @if($article->is_published && (!$article->published_at || $article->published_at->isPast()))
                                                <a href="{{ route('articles.show', $article->slug) }}" target="_blank" title="Lihat Live" class="p-1.5 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition">
                                                    <i class="fas fa-eye text-xs"></i>
                                                </a>
                                            @endif
                                            <a href="{{ route('admin.articles.edit', $article) }}" title="Edit Artikel" class="p-1.5 rounded-lg bg-blue-500/10 hover:bg-blue-500/20 text-blue-500 transition">
                                                <i class="fas fa-edit text-xs"></i>
                                            </a>
                                            <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus Artikel" class="p-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-500 transition">
                                                    <i class="fas fa-trash-alt text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                        <div class="text-3xl mb-2">📭</div>
                                        Belum ada artikel yang dibuat atau sesuai kriteria pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($articles->hasPages())
                    <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $articles->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
