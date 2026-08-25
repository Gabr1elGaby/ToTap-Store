<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pembayaran QRIS') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                <div class="p-8 text-gray-900 dark:text-gray-100 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Pembayaran QRIS Instan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Scan QRIS menggunakan GoPay, OVO, DANA, ShopeePay, BCA, atau M-Banking apa pun.</p>
                    
                    <div class="border border-gray-200 dark:border-gray-700 rounded-2xl p-6 mb-8 max-w-md mx-auto bg-gray-50/50 dark:bg-gray-900/50 shadow-sm">
                        <div class="flex justify-between items-center text-sm mb-3">
                            <span class="text-gray-500 dark:text-gray-400">Order ID</span>
                            <span class="font-mono font-bold text-gray-900 dark:text-white">{{ $order->order_number }}</span>
                        </div>

                        <div class="flex justify-between items-center text-sm mb-5 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-gray-500 dark:text-gray-400">Metode</span>
                            <span class="inline-flex items-center gap-1.5 font-bold text-indigo-600 dark:text-indigo-400">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                QRIS All Payment
                            </span>
                        </div>

                        <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Total Tagihan</p>
                        <p class="text-3xl font-black text-indigo-600 dark:text-indigo-400 mb-6">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>

                        <!-- QRIS Display -->
                        <div class="mb-6 flex flex-col items-center justify-center p-6 bg-white dark:bg-gray-800 border-2 border-indigo-200 dark:border-indigo-800 rounded-2xl shadow-sm">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/Logo_QRIS.svg" alt="QRIS Logo" class="h-6 object-contain mb-4">
                            
                            <!-- Static/Dynamic QR Code for Plan -->
                            <div class="p-2 bg-white rounded-xl shadow-inner border border-gray-100">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode('https://totapstore.com/orders/' . $order->order_number) }}" alt="QRIS" class="w-48 h-48 mx-auto">
                            </div>

                            <p class="mt-4 text-xs font-medium text-gray-600 dark:text-gray-300 text-center">
                                Buka aplikasi e-wallet atau m-banking Anda,<br><span class="font-bold text-gray-800 dark:text-white">lalu scan QR Code di atas.</span>
                            </p>
                        </div>

                        <form action="{{ route('payment.simulate', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-emerald-600 text-white font-bold py-3.5 rounded-xl hover:bg-emerald-700 hover:shadow-lg transition-all text-base flex justify-center items-center gap-2 shadow-emerald-600/20">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Saya Sudah Membayar (Verifikasi)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
