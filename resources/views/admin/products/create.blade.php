<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Software Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen transition-colors duration-200">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.products.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="name">Nama Produk</label>
                            <input class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="name" type="text" name="name" required autofocus />
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="description">Deskripsi</label>
                            <textarea class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="description" name="description" rows="4"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="features">Fitur-fitur</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Pisahkan setiap fitur dengan baris baru (Enter).</span>
                            <textarea class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="features" name="features" rows="5"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="demo_url">App URL (Alamat Akses Aplikasi)</label>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Masukkan link aplikasi (misal: https://totap-kasir-production.up.railway.app).</span>
                            <input class="border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 rounded-xl shadow-sm block mt-1 w-full" id="demo_url" type="url" name="demo_url" />
                        </div>
                        <div class="mb-6 block">
                            <label for="is_active" class="inline-flex items-center cursor-pointer">
                                <input id="is_active" type="checkbox" class="w-5 h-5 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active" value="1" checked>
                                <span class="ms-2.5 text-sm font-bold text-gray-700 dark:text-gray-300">Active (Tampilkan di Publik)</span>
                            </label>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.products.index') }}" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2.5 px-5 rounded-xl text-sm transition">
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm shadow-md transition">
                                Simpan Produk
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>