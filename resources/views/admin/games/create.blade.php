<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nama Game</label>
                        <input type="text" name="name" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                        <input type="text" name="developer" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Upload Thumbnail (Kotak)</label>
                        <input type="file" name="thumbnail" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Upload Cover (Memanjang)</label>
                        <input type="file" name="cover_image" accept="image/*" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Status</label>
                        <select name="is_active" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1">Aktif</option>
                            <option value="0">Sembunyikan</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>