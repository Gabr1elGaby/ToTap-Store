<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.games.update', $game) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nama Game</label>
                        <input type="text" name="name" value="{{ $game->name }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                        <input type="text" name="developer" value="{{ $game->developer }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Upload Thumbnail (Kotak) - Kosongi jika tidak diubah</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                        @if($game->thumbnail)
                            <img src="{{ $game->thumbnail }}" class="h-20 mt-2 rounded border">
                        @endif
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Upload Cover (Memanjang) - Kosongi jika tidak diubah</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image }}" class="h-20 mt-2 rounded border">
                        @endif
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Status</label>
                        <select name="is_active" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1" {{ $game->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !$game->is_active ? 'selected' : '' }}>Sembunyikan</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>