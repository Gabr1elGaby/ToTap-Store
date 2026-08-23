<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product: ') }} {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="name">Name</label>
                            <input class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="name" type="text" name="name" value="{{ $product->name }}" required autofocus />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="description">Description</label>
                            <textarea class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="description" name="description" rows="4">{{ $product->description }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="features">Features (Fitur-fitur)</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Pisahkan setiap fitur dengan baris baru (Enter).</span>
                            <textarea class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="features" name="features" rows="5">{{ $product->features }}</textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="demo_url">App URL (Alamat Akses Aplikasi)</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Masukkan link aplikasi (misal: https://totap-kasir-production.up.railway.app). Pelanggan akan diarahkan ke link ini saat menekan 'Beli Layanan'.</span>
                            <input class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="demo_url" type="url" name="demo_url" value="{{ $product->demo_url }}" />
                        </div>
                        <div class="mb-6 block">
                            <label for="is_active" class="inline-flex items-center cursor-pointer">
                                <input id="is_active" type="checkbox" class="w-5 h-5 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                <span class="ms-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">Active (Tampilkan di Katalog Publik)</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-md transition">
                                Save Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if(str_contains(strtolower($product->name), 'cv'))
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between bg-slate-50 dark:bg-gray-900/80">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <i class="fas fa-file-alt text-indigo-600 dark:text-indigo-400"></i> Pengaturan Harga Template CV
                    </h3>
                </div>
                <div class="p-6">
                    @php
                        $cvTemplates = \Illuminate\Support\Facades\DB::table('cv_templates')->orderBy('id')->get();
                    @endphp
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-slate-50 dark:bg-gray-900/50">
                                <tr>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Template Name</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price (Biaya Unduh)</th>
                                    <th scope="col" class="px-6 py-3.5 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3.5 text-right text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($cvTemplates as $t)
                                <tr class="hover:bg-slate-100 dark:hover:bg-gray-700/60 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $t->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $t->slug }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp{{ number_format($t->price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $t->status === 'active' ? 'bg-green-500/10 text-green-600 dark:text-green-400 border border-green-500/20' : 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20' }}">
                                            {{ ucfirst($t->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.cv-templates.edit', $t->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-indigo-600 dark:text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 border border-indigo-500/30 transition">
                                            <i class="fas fa-edit"></i> Edit Harga
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>