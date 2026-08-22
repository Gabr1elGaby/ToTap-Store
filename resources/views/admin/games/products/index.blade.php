<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Produk untuk: {{ $game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 flex gap-4">
                <a href="{{ route('admin.games.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">&larr; Kembali</a>
                <a href="{{ route('admin.games.products.sync', $game) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Tarik Data Otomatis (VIP Reseller)</a>
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
                            @foreach($products as $prod)
                            <tr>
                                <td class="border-b p-2 text-sm text-gray-500">{{ $prod->product_code }}</td>
                                <td class="border-b p-2">{{ $prod->name }}</td>
                                <td class="border-b p-2">Rp{{ number_format($prod->price_modal, 0, ',', '.') }}</td>
                                <td class="border-b p-2 font-bold text-green-600">Rp{{ number_format($prod->price_sell, 0, ',', '.') }}</td>
                                <td class="border-b p-2 text-sm text-indigo-500">Rp{{ number_format($prod->price_sell - $prod->price_modal, 0, ',', '.') }}</td>
                                <td class="border-b p-2">
                                    <span class="{{ $prod->status == 'available' ? 'text-green-500' : 'text-red-500' }}">{{ ucfirst($prod->status) }}</span>
                                </td>
                                <td class="border-b p-2">
                                    <a href="{{ route('admin.games.products.edit', ['game' => $game->id, 'product' => $prod->id]) }}" class="text-blue-500 hover:underline">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                            @if($products->isEmpty())
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