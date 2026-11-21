<x-app-layout>
    <!-- Quill WYSIWYG Editor Styles -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .ql-toolbar {
            border-top-left-radius: 0.75rem !important;
            border-top-right-radius: 0.75rem !important;
        }
        .ql-container {
            border-bottom-left-radius: 0.75rem !important;
            border-bottom-right-radius: 0.75rem !important;
            min-height: 380px;
            font-family: inherit;
        }
        .dark .ql-toolbar {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .dark .ql-toolbar .ql-stroke { stroke: #94a3b8 !important; }
        .dark .ql-toolbar .ql-fill { fill: #94a3b8 !important; }
        .dark .ql-toolbar .ql-picker { color: #94a3b8 !important; }
        .dark .ql-toolbar button:hover .ql-stroke,
        .dark .ql-toolbar button.ql-active .ql-stroke { stroke: #34d399 !important; }
        .dark .ql-toolbar button:hover .ql-fill,
        .dark .ql-toolbar button.ql-active .ql-fill { fill: #34d399 !important; }
        .dark .ql-toolbar .ql-picker-options {
            background-color: #1e293b !important;
            border-color: #334155 !important;
        }
        .dark .ql-container {
            background-color: #0f172a !important;
            border-color: #334155 !important;
            color: #f1f5f9 !important;
        }
        .dark .ql-editor.ql-blank::before {
            color: #64748b !important;
        }
        .ql-editor img {
            border-radius: 0.75rem;
            margin: 1.25rem auto;
            max-width: 100%;
            height: auto;
            display: block;
            border: 1px solid #334155;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
    </style>

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

            <form id="article-form" action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Konten Utama Artikel --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-5">
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

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Slug URL
                        </label>
                        <input type="text" name="slug" value="{{ old('slug', $article->slug) }}"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-xs text-gray-900 dark:text-white font-mono focus:ring-2 focus:ring-emerald-500">
                        <span class="text-[11px] text-gray-400 mt-1 block">URL: /artikel/{{ $article->slug }}</span>
                    </div>

                    {{-- Upload Gambar Thumbnail / Cover Utama --}}
                    <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="text-emerald-500 text-sm">🖼️</span> Upload Gambar Cover Utama dari Komputer
                            </label>
                            <span class="text-[11px] text-gray-400">Format: JPG, PNG, WEBP (Maks 5MB)</span>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center gap-4">
                            <!-- Preview Box -->
                            <div class="w-32 h-24 sm:w-40 sm:h-28 rounded-xl bg-gray-100 dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 overflow-hidden flex items-center justify-center shrink-0 relative group">
                                @if($article->thumbnail)
                                    <img id="preview-image" src="{{ $article->thumbnail }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                                    <div id="placeholder-box" class="text-center p-2 text-gray-400 hidden">
                                        <i class="fas fa-image text-2xl mb-1 block"></i>
                                        <span class="text-[10px] block font-semibold">Preview Cover</span>
                                    </div>
                                @else
                                    <img id="preview-image" src="" alt="Preview Gambar" class="w-full h-full object-cover hidden">
                                    <div id="placeholder-box" class="text-center p-2 text-gray-400">
                                        <i class="fas fa-image text-2xl mb-1 block"></i>
                                        <span class="text-[10px] block font-semibold">Preview Cover</span>
                                    </div>
                                @endif
                            </div>

                            <!-- File Input Controls -->
                            <div class="flex-1 space-y-2 w-full">
                                <input type="file" name="thumbnail_file" id="thumbnail_file" accept="image/*" onchange="previewThumbnail(this)"
                                    class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-500 file:cursor-pointer cursor-pointer border border-gray-200 dark:border-gray-700 rounded-xl p-1 bg-white dark:bg-gray-800">
                                <input type="hidden" name="thumbnail_base64" id="thumbnail_base64">
                                
                                <div class="text-[11px] text-gray-400">
                                    Pilih file baru dari komputer untuk mengganti gambar cover saat ini.
                                </div>

                                <!-- Secondary URL fallback (if preferred) -->
                                <div class="pt-2 border-t border-gray-200 dark:border-gray-700/60">
                                    <label class="text-[11px] font-semibold text-gray-500 dark:text-gray-400 block mb-1">
                                        Atau ubah melalui link URL (Opsional):
                                    </label>
                                    <input type="url" name="thumbnail" value="{{ old('thumbnail', $article->thumbnail) }}" placeholder="https://..."
                                        class="w-full px-3 py-1.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg text-xs text-gray-900 dark:text-white placeholder-gray-400 focus:ring-1 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">
                            Ringkasan Singkat (Excerpt)
                        </label>
                        <textarea name="excerpt" rows="2"
                            class="w-full px-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    {{-- Isi Lengkap Artikel dengan Visual Editor & Sisip Gambar Fleksibel --}}
                    <div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-2">
                            <label class="text-xs font-bold text-gray-700 dark:text-gray-300 flex items-center gap-1.5">
                                <span>📝</span> Isi Lengkap Artikel <span class="text-red-500">*</span>
                            </label>
                            <span class="text-[11px] text-emerald-500 font-semibold flex items-center gap-1">
                                <i class="fas fa-camera"></i> Anda bisa memasukkan gambar di posisi manapun menggunakan tombol 🖼️ di bilah alat di bawah!
                            </span>
                        </div>

                        <!-- Editor Container -->
                        <div id="editor-container" class="rounded-xl overflow-hidden">
                            {!! old('content', $article->content) !!}
                        </div>
                        <input type="hidden" name="content" id="hidden_content">

                        <div class="mt-2 p-3 bg-blue-500/10 border border-blue-500/20 rounded-xl text-[11px] text-blue-400 space-y-1">
                            <div>📸 <strong>Cara Memasukkan Gambar di Dalam Artikel:</strong></div>
                            <ol class="list-decimal pl-4 space-y-0.5">
                                <li>Arahkan kursor ketik di baris manapun Anda ingin menaruh gambar (misal di bawah Langkah 1 atau antara paragraf).</li>
                                <li>Klik ikon <strong>🖼️ (Gambar)</strong> pada bilah alat editor di atas.</li>
                                <li>Pilih foto dari komputer Anda. Gambar akan langsung otomatis terupload dan muncul tepat di posisi tersebut!</li>
                            </ol>
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

                @php
                    $isScheduled = $article->is_published && $article->published_at && $article->published_at->isFuture();
                    $initialMode = old('publish_mode', $isScheduled ? 'schedule' : 'now');
                @endphp

                {{-- Status & Jadwal Publikasi --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700/60">
                        <div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                                <span class="text-blue-500">🚀</span> Status Publikasi
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                Status saat ini: 
                                @if($isScheduled)
                                    <strong class="text-blue-500">⏰ Terjadwal tayang {{ $article->published_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</strong>
                                @elseif($article->is_published)
                                    <strong class="text-emerald-500">✓ Published (Aktif & Tayang)</strong>
                                @else
                                    <strong class="text-amber-500">Draft (Belum Tayang)</strong>
                                @endif
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_published" id="is_published_toggle" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }} class="sr-only peer" onchange="togglePublishOptions(this.checked)">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-emerald-600"></div>
                            <span class="ml-3 text-xs font-bold text-gray-700 dark:text-gray-300" id="publish_toggle_label">Publikasikan</span>
                        </label>
                    </div>

                    {{-- Opsi Waktu Tayang / Jadwal Upload --}}
                    <div id="schedule_section" class="space-y-3 pt-1 {{ old('is_published', $article->is_published) ? '' : 'hidden' }}">
                        <label class="text-xs font-bold text-gray-800 dark:text-gray-200 block">
                            ⏰ Opsi Waktu Tayang:
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 cursor-pointer hover:border-emerald-500 transition">
                                <input type="radio" name="publish_mode" value="now" id="mode_now" {{ $initialMode === 'now' ? 'checked' : '' }} onchange="toggleScheduleInput(false)" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="text-xs font-bold text-gray-900 dark:text-white">⚡ Terbitkan Sekarang</div>
                                    <div class="text-[11px] text-gray-400">Langsung tayang dan didaftarkan ke sitemap.xml hari ini.</div>
                                </div>
                            </label>

                            <label class="flex items-center gap-3 p-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 cursor-pointer hover:border-emerald-500 transition">
                                <input type="radio" name="publish_mode" value="schedule" id="mode_schedule" {{ $initialMode === 'schedule' ? 'checked' : '' }} onchange="toggleScheduleInput(true)" class="text-emerald-600 focus:ring-emerald-500">
                                <div>
                                    <div class="text-xs font-bold text-gray-900 dark:text-white">📅 Jadwalkan Tanggal & Jam Tayang</div>
                                    <div class="text-[11px] text-gray-400">Pilih hari & jam kapan artikel akan otomatis tayang.</div>
                                </div>
                            </label>
                        </div>

                        <div id="schedule_datetime_box" class="{{ $initialMode === 'schedule' ? '' : 'hidden' }} p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 space-y-2">
                            <label class="block text-xs font-bold text-emerald-400">
                                Tentukan Tanggal & Jam Tayang (WIB):
                            </label>
                            <input type="datetime-local" name="published_at" id="published_at_input" value="{{ old('published_at', ($article->published_at ? $article->published_at->setTimezone('Asia/Jakarta')->format('Y-m-d\TH:i') : now('Asia/Jakarta')->addHour()->format('Y-m-d\TH:i'))) }}"
                                class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-gray-900 border border-emerald-500/30 rounded-xl text-xs text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                            <div class="text-[11px] text-emerald-400/90">
                                💡 <strong>Otomatis:</strong> Saat tanggal & jam tayang tersebut tiba, artikel akan otomatis muncul di website dan otomatis didaftarkan ke <code>/sitemap.xml</code> agar segera dibaca Google.
                            </div>
                        </div>
                    </div>
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

    <!-- Quill WYSIWYG Scripts -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        function previewThumbnail(input) {
            const preview = document.getElementById('preview-image');
            const placeholder = document.getElementById('placeholder-box');
            const hiddenBase64 = document.getElementById('thumbnail_base64');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = new Image();
                    img.onload = function() {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        if (placeholder) placeholder.classList.add('hidden');

                        // Automatic Smart Web Compression: downscale large images (e.g. Unsplash 10MB+) to max 1280px WebP Base64
                        try {
                            const canvas = document.createElement('canvas');
                            let w = img.width;
                            let h = img.height;
                            const maxDim = 1280;
                            if (w > maxDim || h > maxDim) {
                                if (w > h) {
                                    h = Math.round((h * maxDim) / w);
                                    w = maxDim;
                                } else {
                                    w = Math.round((w * maxDim) / h);
                                    h = maxDim;
                                }
                            }
                            canvas.width = w;
                            canvas.height = h;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, w, h);

                            const compressedData = canvas.toDataURL('image/webp', 0.85);
                            if (hiddenBase64) {
                                hiddenBase64.value = compressedData;
                            }
                        } catch (err) {
                            console.warn('Canvas optimization skipped:', err);
                        }
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        }

        // Initialize Quill Editor
        const quill = new Quill('#editor-container', {
            theme: 'snow',
            placeholder: 'Tulis isi artikel Anda di sini... Anda bebas mengetik, memformat teks, dan menyisipkan gambar di posisi manapun!',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['blockquote', 'code-block'],
                        ['link', 'image'],
                        ['clean']
                    ],
                    handlers: {
                        image: imageHandler
                    }
                }
            }
        });

        // Custom Image Handler: upload image directly from computer to server with auto compression
        function imageHandler() {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');
            input.click();

            input.onchange = async () => {
                const file = input.files[0];
                if (file) {
                    const range = quill.getSelection(true);
                    quill.insertText(range.index, '⏳ Mengupload gambar...', { 'italic': true, 'color': '#34d399' });

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = new Image();
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            let w = img.width;
                            let h = img.height;
                            const maxDim = 1280;
                            if (w > maxDim || h > maxDim) {
                                if (w > h) {
                                    h = Math.round((h * maxDim) / w);
                                    w = maxDim;
                                } else {
                                    w = Math.round((w * maxDim) / h);
                                    h = maxDim;
                                }
                            }
                            canvas.width = w;
                            canvas.height = h;
                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, w, h);

                            canvas.toBlob(async function(blob) {
                                const sendFile = blob ? new File([blob], 'art_' + Date.now() + '.webp', { type: 'image/webp' }) : file;
                                const formData = new FormData();
                                formData.append('image', sendFile);
                                formData.append('_token', '{{ csrf_token() }}');

                                try {
                                    const response = await fetch('{{ route("admin.articles.upload-content-image") }}', {
                                        method: 'POST',
                                        body: formData
                                    });
                                    const data = await response.json();

                                    quill.deleteText(range.index, 23);

                                    if (data.success && data.url) {
                                        quill.insertEmbed(range.index, 'image', data.url);
                                        quill.setSelection(range.index + 1);
                                    } else {
                                        alert('Gagal mengupload gambar. Silakan coba lagi.');
                                    }
                                } catch (err) {
                                    quill.deleteText(range.index, 23);
                                    alert('Terjadi kesalahan jaringan saat mengupload gambar.');
                                }
                            }, 'image/webp', 0.88);
                        };
                        img.src = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            };
        }

        // Sync Quill HTML to hidden input before submitting form & bypass PHP upload limits
        document.getElementById('article-form').addEventListener('submit', function() {
            const content = quill.root.innerHTML;
            document.getElementById('hidden_content').value = content;

            const hiddenBase64 = document.getElementById('thumbnail_base64');
            const fileInput = document.getElementById('thumbnail_file');
            if (hiddenBase64 && hiddenBase64.value && hiddenBase64.value.length > 100) {
                fileInput.value = '';
            }
        });

        // Publish & Schedule UI controls
        function togglePublishOptions(isPublished) {
            const section = document.getElementById('schedule_section');
            if (section) {
                if (isPublished) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            }
        }

        function toggleScheduleInput(isScheduled) {
            const box = document.getElementById('schedule_datetime_box');
            if (box) {
                if (isScheduled) {
                    box.classList.remove('hidden');
                } else {
                    box.classList.add('hidden');
                }
            }
        }
    </script>
</x-app-layout>
