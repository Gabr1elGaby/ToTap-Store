<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span class="text-emerald-500">✍️</span> {{ __('Tulis Artikel SEO Baru') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Buat artikel SEO baru untuk mendongkrak pencarian organik Google ToTap Store.
                </p>
            </div>
            <a href="{{ route('admin.articles.index') }}" class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition flex items-center gap-1.5 border border-gray-300 dark:border-gray-700">
                <i class="fas fa-arrow-left text-xs"></i> Kembali ke Daftar
            </a>
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

            <form action="{{ route('admin.articles.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Konten Utama Artikel --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 flex items-center gap-2">
                        <span>📄</span> Informasi Artikel
                    </h3>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Judul Artikel <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Cara Top Up Diamond Mobile Legends Termurah via QRIS 24 Jam"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select name="category" required class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Penulis (Author)
                            </label>
                            <input type="text" name="author_name" value="{{ old('author_name', 'ToTap Editorial Team') }}" placeholder="ToTap Editorial Team"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                Custom Slug URL (Opsional)
                            </label>
                            <input type="text" name="slug" value="{{ old('slug') }}" placeholder="Dibuat otomatis dari judul jika dikosongkan"
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white font-mono text-xs focus:ring-2 focus:ring-emerald-500">
                            <span class="text-[11px] text-gray-400 mt-1 block">URL: /artikel/nama-slug-kamu</span>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                                URL Thumbnail / Gambar Cover (Opsional)
                            </label>
                            <input type="url" name="thumbnail" value="{{ old('thumbnail') }}" placeholder="https://images.unsplash.com/photo-..."
                                class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Ringkasan Singkat (Excerpt)
                        </label>
                        <textarea name="excerpt" rows="2" placeholder="Ringkasan 1-2 kalimat pengantar yang menarik pembaca..."
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('excerpt') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Isi Lengkap Artikel (Format HTML Didukung) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" rows="14" required placeholder="Tulis artikel di sini. Anda dapat menggunakan tag HTML seperti <h2>Subjudul</h2>, <p>Paragraf</p>, <strong>Teks Tebal</strong>, <ul><li>Poin</li></ul>, <ol><li>Langkah 1</li></ol>, dll."
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500 leading-relaxed">{{ old('content') }}</textarea>
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
                            Meta Title (Judul di Hasil Pencarian Google)
                        </label>
                        <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="Contoh: Cara Top Up ML Murah & Instan 24 Jam via QRIS - ToTap Store"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[11px] text-gray-400 mt-1 block">Direkomendasikan 50 - 60 karakter agar tampil penuh di Google.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Meta Description (Deskripsi Cuplikan Google)
                        </label>
                        <textarea name="meta_description" rows="2" placeholder="Contoh: Panduan lengkap beli diamond ML murah dan aman di ToTap Store. Transaksi otomatis 24 jam via QRIS langsung masuk ke akun."
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('meta_description') }}</textarea>
                        <span class="text-[11px] text-gray-400 mt-1 block">Direkomendasikan 120 - 160 karakter.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Meta Keywords (Kata Kunci Google, dipisahkan koma)
                        </label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="top up ml murah, diamond mlbb, totap store ml, beli diamond ml qris"
                            class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>

                {{-- Status Publikasi --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm flex items-center justify-between">
                    <div>
                        <div class="font-bold text-sm text-gray-900 dark:text-white">Status Publikasi</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">Jika dicentang, artikel akan langsung terbit dan didaftarkan ke sitemap.xml.</div>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                        <span class="ml-3 text-xs font-bold text-gray-700 dark:text-gray-300">Terbitkan Langsung</span>
                    </label>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.articles.index') }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:opacity-90 shadow-md shadow-emerald-500/20 transition flex items-center gap-1.5">
                        <i class="fas fa-save"></i> Simpan & Publikasikan
                    </button>
                </div>

            </form>

        </div>
    </div>
</x-app-layout>
