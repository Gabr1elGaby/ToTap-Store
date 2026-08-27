<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-700 transition shadow-sm">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Game') }}: {{ $game->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen" x-data="gameEditForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm font-bold text-sm">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-400 p-5 rounded-2xl shadow-sm">
                    <strong class="font-bold flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle"></i> Gagal Menyimpan:
                    </strong>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl border border-gray-200 dark:border-gray-700 p-8">
                <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nama Game / Layanan <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="name" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                            <input type="text" name="developer" x-model="developer" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Deskripsi Singkat Layanan (Tampil di Bawah Thumbnail)</label>
                        <textarea name="description" x-model="description" rows="2" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-medium" placeholder="Tuliskan deskripsi singkat mengenai layanan atau game ini..."></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Kategori</label>
                            <select name="category" x-model="category" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="Mobile Game">Mobile Game</option>
                                <option value="PC Game">PC Game</option>
                                <option value="Aplikasi Premium">Aplikasi Premium</option>
                                <option value="Voucher">Voucher</option>
                                <option value="App & Entertainment">App & Entertainment (Streaming)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status Game</label>
                            <select name="is_active" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="1" {{ $game->is_active ? 'selected' : '' }}>Aktif (Tampil di Website)</option>
                                <option value="0" {{ !$game->is_active ? 'selected' : '' }}>Sembunyikan / Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Thumbnail (Kotak) - Kosongi jika tidak diubah</label>
                            <input type="file" name="thumbnail" accept="image/*" 
                                   onchange="const [file] = this.files; if(file){ const p = document.getElementById('thumb-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                                   class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs">
                            <img id="thumb-preview" src="{{ $game->thumbnail }}" 
                                 class="h-20 w-20 object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 {{ empty($game->thumbnail) ? 'hidden' : '' }}" 
                                 onerror="this.classList.add('hidden')">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Cover (Memanjang) - Kosongi jika tidak diubah</label>
                            <input type="file" name="cover_image" accept="image/*" 
                                   onchange="const [file] = this.files; if(file){ const p = document.getElementById('cover-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                                   class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs">
                            <img id="cover-preview" src="{{ $game->cover_image }}" 
                                 class="h-20 w-full max-w-xs object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 {{ empty($game->cover_image) ? 'hidden' : '' }}" 
                                 onerror="this.classList.add('hidden')">
                        </div>
                    </div>

                    <!-- Target Fields -->
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 space-y-4">
                        <div class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-500"></i>
                            Data Akun yang Diperlukan Pembeli
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Input 1 (Data Akun Utama) <span class="text-red-500">*</span></label>
                                <input type="text" name="target_field_1" x-model="target_field_1" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-semibold" required>
                            </div>
                            <div x-show="requires_zone_id">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Input 2 (Zone / Server ID)</label>
                                <input type="text" name="target_field_2" x-model="target_field_2" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-semibold">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Teks Keterangan di Bawah Input (Opsional)</label>
                            <input type="text" name="target_field_1_help" x-model="target_field_1_help" placeholder="Contoh: Data akun & password akan otomatis muncul di layar dan invoice Anda" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-medium">
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" name="requires_zone_id" id="requires_zone_id" x-model="requires_zone_id" value="1" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300 cursor-pointer">
                            <label for="requires_zone_id" class="text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer">
                                Game ini membutuhkan 2 kolom input (seperti Mobile Legends / Genshin Impact)
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Panduan / Cara Cek ID Akun (Kotak Biru di Atas)</label>
                        <textarea name="guide_text" x-model="guide_text" rows="3" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-medium" placeholder="Tuliskan petunjuk cara melihat ID game..."></textarea>
                    </div>

                    <div class="pt-4 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
                        <button type="button" 
                                onclick="if(confirm('Apakah Anda yakin ingin menghapus game {{ $game->name }} beserta seluruh produknya?')) { document.getElementById('delete-game-form').submit(); }"
                                class="px-5 py-3 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-600 dark:text-red-400 font-bold text-xs border border-red-500/30 transition flex items-center gap-1.5 cursor-pointer">
                            <i class="fas fa-trash-alt"></i> Hapus Game
                        </button>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('admin.games.index') }}" class="px-5 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                                Batal
                            </a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-600/30 transition cursor-pointer flex items-center gap-2 text-sm">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>

                <form id="delete-game-form" action="{{ route('admin.games.destroy', $game) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>

    <script>
        function gameEditForm() {
            return {
                name: @json($game->name),
                developer: @json($game->developer ?? ''),
                description: @json($game->description ?? ''),
                category: @json($game->category ?? 'Mobile Game'),
                target_field_1: @json($game->target_field_1 ?? 'User ID'),
                target_field_2: @json($game->target_field_2 ?? ''),
                target_field_1_help: @json($game->target_field_1_help ?? ''),
                requires_zone_id: @json((bool)$game->requires_zone_id),
                guide_text: @json($game->guide_text ?? '')
            };
        }
    </script>
</x-app-layout>