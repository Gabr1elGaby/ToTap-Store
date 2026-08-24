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

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-600 dark:text-green-400 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-sm font-bold text-sm mb-6">
                    <i class="fas fa-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-600 dark:text-red-400 p-5 rounded-2xl mb-6 shadow-sm">
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
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl border border-gray-200 dark:border-gray-700 p-8">
                <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nama Game</label>
                        <input type="text" name="name" value="{{ $game->name }}" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" required>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                        <input type="text" name="developer" value="{{ $game->developer }}" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Thumbnail (Kotak) - Kosongi jika tidak diubah</label>
                        <input type="file" name="thumbnail" accept="image/*" 
                               onchange="const [file] = this.files; if(file){ const p = document.getElementById('thumb-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                               class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <img id="thumb-preview" src="{{ $game->thumbnail }}" 
                             class="h-24 w-24 object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 {{ empty($game->thumbnail) ? 'hidden' : '' }}" 
                             onerror="this.classList.add('hidden')">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Cover (Memanjang) - Kosongi jika tidak diubah</label>
                        <input type="file" name="cover_image" accept="image/*" 
                               onchange="const [file] = this.files; if(file){ const p = document.getElementById('cover-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                               class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        <img id="cover-preview" src="{{ $game->cover_image }}" 
                             class="h-28 w-full max-w-md object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 {{ empty($game->cover_image) ? 'hidden' : '' }}" 
                             onerror="this.classList.add('hidden')">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Label Input 1 (Data Akun Utama)</label>
                            <input type="text" name="target_field_1" value="{{ $game->target_field_1 }}" placeholder="Contoh: User ID / Riot ID / Player ID" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Label Input 2 (Zone / Server ID)</label>
                            <input type="text" name="target_field_2" value="{{ $game->target_field_2 }}" placeholder="Contoh: Zone ID / Server" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                        <input type="checkbox" name="requires_zone_id" id="requires_zone_id" value="1" {{ $game->requires_zone_id ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300">
                        <label for="requires_zone_id" class="text-sm font-bold text-gray-700 dark:text-gray-300 cursor-pointer">
                            Game ini membutuhkan 2 kolom input (seperti Mobile Legends / Genshin Impact)
                        </label>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Panduan / Cara Top Up (Opsional)</label>
                        <textarea name="guide_text" rows="3" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Tuliskan petunjuk pengisian ID untuk game ini...">{{ $game->guide_text }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status Game</label>
                        <select name="is_active" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white">
                            <option value="1" {{ $game->is_active ? 'selected' : '' }}>Aktif (Tampil di Toko)</option>
                            <option value="0" {{ !$game->is_active ? 'selected' : '' }}>Sembunyikan</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg transition cursor-pointer flex items-center gap-2">
                            <i class="fas fa-save"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>