<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span class="text-blue-500">✏️</span> {{ __('Edit Artikel') }}: <span class="text-emerald-500 font-mono text-sm truncate max-w-sm">{{ $article->title }}</span>
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Perbarui konten, optimasi SEO, atau ubah status artikel.
                </p>
            </div>
            <div class="flex items-center gap-2">
                @if($article->is_published)
                    <a href="{{ route('articles.show', $article->slug) }}" target="_blank" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center gap-1.5 border border-gray-300 dark:border-gray-700">
                        <i class="fas fa-eye text-xs"></i> Lihat Live
                    </a>
                @endif
                <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center gap-1.5 border border-gray-300 dark:border-gray-700">
                    <i class="fas fa-arrow-left text-xs"></i> Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if ($errors->any())
                <div class="p-4 bg-red-500/10 border border-red-500/30 text-red-500 rounded-xl text-xs space-y-1">
                    <div class="font-bold">Terdapat beberapa kesalahan:</div>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.articles.update', $article) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Konten Utama Artikel --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                        <span>📄</span> Informasi Artikel
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $article->title) }}" required
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category', $article->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Penulis (Author)
                            </label>
                            <input type="text" name="author_name" value="{{ old('author_name', $article->author_name) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Slug URL
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $article->slug) }}"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500">
                            <span class="text-[11px] text-gray-400 mt-1 block">URL: /artikel/{{ $article->slug }}</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                URL Thumbnail / Gambar Cover
                            </label>
                            <input type="url" name="thumbnail" value="{{ old('thumbnail', $article->thumbnail) }}" placeholder="https://..."
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Ringkasan Singkat (Excerpt)
                        </label>
                        <textarea name="excerpt" rows="2"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Isi Lengkap Artikel (Format HTML Didukung) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" rows="14" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('content', $article->content) }}</textarea>
                        <div class="mt-2 p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl text-[11px] text-blue-400">
                            💡 <strong>Tips Penulisan SEO:</strong> Gunakan tag <code>&lt;h2&gt;</code> untuk sub-judul topik utama, dan <code>&lt;h3&gt;</code> untuk rincian. Masukkan kata kunci seperti "Top Up Game", nama game, dan sebutkan ToTap Store secara alami.
                        </div>
                    </div>
                </div>

                {{-- Pengaturan SEO & Meta Tags --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                        <span>🔍</span> Optimasi Google SEO (Search Engine Optimization)
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Meta Title (Judul di Google)
                        </label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', $article->meta_title) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Meta Description (Deskripsi Cuplikan Google)
                        </label>
                        <textarea name="meta_description" rows="2"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('meta_description', $article->meta_description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Meta Keywords (Kata Kunci Google, dipisahkan koma)
                        </label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $article->meta_keywords) }}"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                {{-- Status Publikasi --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white">Status Publikasi</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            Status saat ini: 
                            <strong class="{{ $article->is_published ? 'text-emerald-500' : 'text-amber-500' }}">
                                {{ $article->is_published ? 'Published (Aktif & Tayang)' : 'Draft (Belum Tayang)' }}
                            </strong>
                            @if($article->published_at)
                                (Dirilis {{ $article->published_at->format('d M Y') }})
                            @endif
                        </div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-xs font-bold text-gray-700 dark:text-gray-300">Terbitkan</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:opacity-90 shadow-md shadow-blue-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-save"></i> Perbarui Artikel
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
