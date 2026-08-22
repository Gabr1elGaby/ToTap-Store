<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Daftar Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex justify-between">
                <a href="{{ route('admin.games.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Tambah Game Baru</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Game</th>
                                <th class="border-b p-2">Kategori</th>
                                <th class="border-b p-2">Status</th>
                                <th class="border-b p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($games as $game)
                            <tr>
                                <td class="border-b p-2">{{ $game->name }}</td>
                                <td class="border-b p-2">{{ $game->category }}</td>
                                <td class="border-b p-2">
                                    <span class="{{ $game->is_active ? 'text-green-500' : 'text-red-500' }}">{{ $game->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('admin.games.products.index', $game) }}" class="text-indigo-500 hover:underline mr-2">Atur Produk</a>
                                    <a href="{{ route('admin.games.edit', $game) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>