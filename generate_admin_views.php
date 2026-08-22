<?php

$dir = 'resources/views/admin/games';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$prodDir = "$dir/products";
if (!is_dir($prodDir)) mkdir($prodDir, 0755, true);

// games.index
$gamesIndex = <<<HTML
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
                            @foreach(\$games as \$game)
                            <tr>
                                <td class="border-b p-2">{{ \$game->name }}</td>
                                <td class="border-b p-2">{{ \$game->category }}</td>
                                <td class="border-b p-2">
                                    <span class="{{ \$game->is_active ? 'text-green-500' : 'text-red-500' }}">{{ \$game->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('admin.games.products.index', \$game) }}" class="text-indigo-500 hover:underline mr-2">Atur Produk</a>
                                    <a href="{{ route('admin.games.edit', \$game) }}" class="text-blue-500 hover:underline mr-2">Edit</a>
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
HTML;
file_put_contents("$dir/index.blade.php", $gamesIndex);

// games.create
$gamesCreate = <<<HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.games.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nama Game</label>
                        <input type="text" name="name" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required placeholder="Misal: Mobile Legends">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                        <input type="text" name="developer" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" placeholder="Misal: Moonton">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">URL Gambar Thumbnail (Kotak)</label>
                        <input type="text" name="thumbnail" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">URL Gambar Cover (Memanjang)</label>
                        <input type="text" name="cover_image" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Butuh Zone ID?</label>
                        <select name="requires_zone_id" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1">Ya (Misal: Mobile Legends)</option>
                            <option value="0">Tidak (Misal: Free Fire)</option>
                        </select>
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
HTML;
file_put_contents("$dir/create.blade.php", $gamesCreate);

// games.edit
$gamesEdit = <<<HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Game') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.games.update', \$game) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nama Game</label>
                        <input type="text" name="name" value="{{ \$game->name }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                        <input type="text" name="developer" value="{{ \$game->developer }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">URL Gambar Thumbnail (Kotak)</label>
                        <input type="text" name="thumbnail" value="{{ \$game->thumbnail }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">URL Gambar Cover (Memanjang)</label>
                        <input type="text" name="cover_image" value="{{ \$game->cover_image }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Butuh Zone ID?</label>
                        <select name="requires_zone_id" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1" {{ \$game->requires_zone_id ? 'selected' : '' }}>Ya</option>
                            <option value="0" {{ !\$game->requires_zone_id ? 'selected' : '' }}>Tidak</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Status</label>
                        <select name="is_active" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="1" {{ \$game->is_active ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ !\$game->is_active ? 'selected' : '' }}>Sembunyikan</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
HTML;
file_put_contents("$dir/edit.blade.php", $gamesEdit);


// products.index
$productsIndex = <<<HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Produk untuk: {{ \$game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex gap-4">
                <a href="{{ route('admin.games.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">&larr; Kembali</a>
                <a href="{{ route('admin.games.products.sync', \$game) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Tarik Data Otomatis (VIP Reseller)</a>
            </div>
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr>
                                <th class="border-b p-2">Kode</th>
                                <th class="border-b p-2">Nama Produk</th>
                                <th class="border-b p-2">Modal</th>
                                <th class="border-b p-2">Harga Jual</th>
                                <th class="border-b p-2">Untung</th>
                                <th class="border-b p-2">Status</th>
                                <th class="border-b p-2">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(\$products as \$prod)
                            <tr>
                                <td class="border-b p-2 text-sm text-gray-500">{{ \$prod->product_code }}</td>
                                <td class="border-b p-2">{{ \$prod->name }}</td>
                                <td class="border-b p-2">Rp{{ number_format(\$prod->price_modal, 0, ',', '.') }}</td>
                                <td class="border-b p-2 font-bold text-green-600">Rp{{ number_format(\$prod->price_sell, 0, ',', '.') }}</td>
                                <td class="border-b p-2 text-sm text-indigo-500">Rp{{ number_format(\$prod->price_sell - \$prod->price_modal, 0, ',', '.') }}</td>
                                <td class="border-b p-2">
                                    <span class="{{ \$prod->status == 'available' ? 'text-green-500' : 'text-red-500' }}">{{ ucfirst(\$prod->status) }}</span>
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('admin.games.products.edit', ['game' => \$game->id, 'product' => \$prod->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                            @if(\$products->isEmpty())
                            <tr>
                                <td colspan="7" class="text-center p-4">Belum ada produk. Silakan Tarik Data Otomatis.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
HTML;
file_put_contents("$prodDir/index.blade.php", $productsIndex);


// products.sync
$productsSync = <<<HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tarik Data VIP Reseller: {{ \$game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.games.products.sync.process', \$game) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Kata Kunci Pencarian (Filter)</label>
                        <p class="text-sm text-gray-500 mb-2">Sistem akan mencari game di VIP Reseller yang mengandung kata ini. Harus sama persis. Misal: <strong>Mobile Legends</strong></p>
                        <input type="text" name="filter_value" value="{{ \$game->name }}" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Markup Keuntungan (Rupiah)</label>
                        <p class="text-sm text-gray-500 mb-2">Keuntungan bersih yang ingin Anda ambil per transaksi. Misal isi 2000, maka jika modalnya Rp 15.000, sistem otomatis menjual di web seharga Rp 17.000.</p>
                        <input type="number" name="markup_flat" value="2000" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">
                        Mulai Tarik Data & Update Harga
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
HTML;
file_put_contents("$prodDir/sync.blade.php", $productsSync);


// products.edit
$productsEdit = <<<HTML
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Harga: {{ \$product->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.games.products.update', ['game' => \$game->id, 'product' => \$product->id]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Nama Produk (Bisa diubah jika nama dari supplier terlalu jelek)</label>
                        <input type="text" name="name" value="{{ \$product->name }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Harga Modal (Dari Supplier)</label>
                        <input type="text" value="Rp{{ number_format(\$product->price_modal, 0, ',', '.') }}" class="w-full mt-1 rounded bg-gray-200 dark:bg-gray-900 dark:text-gray-500" disabled>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold">Harga Jual (Di Website Anda)</label>
                        <input type="number" name="price_sell" value="{{ \$product->price_sell }}" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" class="w-full mt-1 rounded dark:bg-gray-700 dark:text-white">
                            <option value="available" {{ \$product->status == 'available' ? 'selected' : '' }}>Tersedia (Available)</option>
                            <option value="empty" {{ \$product->status == 'empty' ? 'selected' : '' }}>Kosong / Gangguan (Empty)</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
HTML;
file_put_contents("$prodDir/edit.blade.php", $productsEdit);

echo "Admin views generated.\n";

