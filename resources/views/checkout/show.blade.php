<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100">
                    <h3 class="text-2xl font-bold mb-6">Ringkasan Pesanan</h3>
                    
                    <div class="border rounded-lg p-6 mb-8">
                        <div class="flex justify-between items-center border-b pb-4 mb-4">
                            <div>
                                <p class="text-gray-500 text-sm">Produk</p>
                                <p class="text-xl font-bold">{{ $plan->product->name }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-500 text-sm">Paket</p>
                                <p class="text-xl font-bold">{{ $plan->name }}</p>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-gray-600">Durasi</p>
                            <p class="font-semibold">{{ $plan->duration_days }} hari</p>
                        </div>
                        <div class="flex justify-between items-center mt-4 text-xl font-bold">
                            <p>Total Pembayaran</p>
                            <p class="text-indigo-600">Rp {{ number_format($plan->price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <form action="{{ route('checkout.process', $plan->id) }}" method="POST" x-data="{ useOtherEmail: false }">
                        @csrf
                        <div class="mb-8">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">Informasi Akun Kasir</h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                <!-- Option 1: Own Email -->
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-5 shadow-sm hover:bg-gray-50 transition-colors focus:outline-none"
                                       :class="{'border-indigo-600 ring-2 ring-indigo-600': !useOtherEmail, 'border-gray-200': useOtherEmail}">
                                    <input type="radio" name="email_choice" value="own" class="sr-only" checked @change="useOtherEmail = false">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-md font-bold text-gray-900 mb-1">Akun Saat Ini</span>
                                            <span class="flex items-center text-sm text-gray-600">{{ Auth::user()->email }}</span>
                                        </span>
                                    </span>
                                    <svg x-show="!useOtherEmail" class="h-6 w-6 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </label>

                                <!-- Option 2: Other Email -->
                                <label class="relative flex cursor-pointer rounded-lg border bg-white p-5 shadow-sm hover:bg-gray-50 transition-colors focus:outline-none"
                                       :class="{'border-indigo-600 ring-2 ring-indigo-600': useOtherEmail, 'border-gray-200': !useOtherEmail}">
                                    <input type="radio" name="email_choice" value="other" class="sr-only" @change="useOtherEmail = true">
                                    <span class="flex flex-1">
                                        <span class="flex flex-col">
                                            <span class="block text-md font-bold text-gray-900 mb-1">Gunakan Email Lain</span>
                                            <span class="flex items-center text-sm text-gray-600">Buat akun baru untuk Admin Kasir</span>
                                        </span>
                                    </span>
                                    <svg x-show="useOtherEmail" style="display: none;" class="h-6 w-6 text-indigo-600" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                                    </svg>
                                </label>
                            </div>

                            <div x-show="!useOtherEmail" class="bg-gray-50 p-5 rounded-lg border border-gray-200 flex items-start space-x-3">
                                <svg class="w-6 h-6 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div>
                                    <p class="text-sm text-gray-700"><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                                    <p class="text-sm text-gray-700 mt-1">Sistem akan menggunakan password akun Anda saat ini untuk login ke Aplikasi Kasir.</p>
                                </div>
                            </div>

                            <div x-show="useOtherEmail" style="display: none;" class="bg-white p-6 rounded-lg border border-indigo-100 shadow-inner">
                                <h5 class="font-bold text-indigo-900 mb-4">Lengkapi Data Admin Baru</h5>
                                <div class="grid grid-cols-1 gap-5">
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                                        <input type="text" name="name" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2" :required="useOtherEmail" placeholder="Budi Santoso">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Email</label>
                                            <input type="email" name="email" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2" :required="useOtherEmail" placeholder="admin@toko.com">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700">Nomor HP</label>
                                            <input type="text" name="phone" class="mt-1.5 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm px-4 py-2" :required="useOtherEmail" placeholder="08123456789">
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 flex items-center text-sm text-indigo-700 bg-indigo-50 p-3 rounded-md">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    Password akan dibuatkan secara otomatis dan ditampilkan setelah Anda menyelesaikan pembayaran.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-4 rounded-xl hover:bg-indigo-700 hover:shadow-lg transition-all text-lg flex justify-center items-center">
                            <span>Lanjut ke Pembayaran</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
