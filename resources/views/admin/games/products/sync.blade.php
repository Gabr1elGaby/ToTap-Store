<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tarik Data VIP Reseller: {{ $game->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">{{ session('error') }}</div>
                @endif

                <form action="{{ route('admin.games.products.sync.process', $game) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Kata Kunci Pencarian (Filter)</label>
                        <p class="text-sm text-gray-500 mb-2">Sistem akan mencari game di VIP Reseller yang mengandung kata ini. Harus sama persis. Misal: <strong>Mobile Legends</strong></p>
                        <input type="text" name="filter_value" value="{{ $game->name }}" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                                        <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Markup Persentase (%)</label>
                        <p class="text-sm text-gray-500 mb-2">Ambil untung berdasarkan persentase (cocok agar barang murah tidak kemahalan, dan barang mahal tetap untung besar). Misal isi 2 untuk untung 2%.</p>
                        <input type="number" step="0.1" name="markup_percent" value="2" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Markup Tambahan (Rupiah Flat)</label>
                        <p class="text-sm text-gray-500 mb-2">Tambahan keuntungan rupiah flat. Total Harga = Modal + (Modal * Persentase) + Markup Rupiah. Isi 0 jika hanya ingin pakai persentase.</p>
                        <input type="number" name="markup_flat" value="300" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white" required>
                    </div>

                                        <!-- SECTION DISKON MASSAL -->
                    <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-700 rounded-lg mb-6">
                        <div class="mb-3">
                            <label class="flex items-center">
                                <input type="checkbox" name="mass_promo_active" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <span class="ml-2 text-gray-700 dark:text-gray-300 font-bold">Aktifkan Trik Diskon Coret Masal? (Flash Sale)</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-1 ml-6">Jika dicentang, seluruh produk yang ditarik akan langsung dilabeli "PROMO" dengan harga normal palsu yang dicoret, seolah-olah Anda sedang memberikan diskon besar-besaran!</p>
                        </div>
                        
                        <div class="ml-6">
                            <label class="block text-gray-700 dark:text-gray-300 font-bold mb-2">Tinggikan Harga Normal Sebesar (%)</label>
                            <p class="text-sm text-gray-500 mb-2">Misal isi 10. Maka Harga Normal (Coret) akan dibuat 10% lebih mahal dari Harga Jual asli Anda.</p>
                            <input type="number" step="0.1" name="mass_promo_percent" value="10" min="0" class="w-full rounded dark:bg-gray-700 dark:text-white">
                        </div>
                    </div>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full">
                        Mulai Tarik Data & Update Harga
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>