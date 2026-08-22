<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembayaran Berhasil') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 text-center">
                    
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>

                    <h3 class="text-3xl font-bold mb-2">Pembayaran Berhasil!</h3>
                    <p class="text-gray-500 mb-8">Terima kasih, pesanan Anda <strong>{{ $order->order_number }}</strong> telah lunas.</p>
                    
                    @if(session('generated_password') && session('checkout_email'))
                        <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-6 max-w-md mx-auto text-left mb-8">
                            <h4 class="font-bold text-indigo-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Informasi Akun Anda
                            </h4>
                            <p class="text-sm text-indigo-700 mb-4">
                                Akun Anda telah berhasil dibuat. Silakan gunakan kredensial berikut untuk login ke aplikasi (Kasir & Dashboard). <strong>Harap simpan password ini dengan baik!</strong>
                            </p>
                            
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs text-indigo-500 uppercase tracking-wider font-semibold">Email</p>
                                    <p class="font-mono text-lg text-gray-900 bg-white px-3 py-2 rounded border">{{ session('checkout_email') }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-indigo-500 uppercase tracking-wider font-semibold">Password</p>
                                    <p class="font-mono text-lg text-gray-900 bg-white px-3 py-2 rounded border">{{ session('generated_password') }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-50 border rounded-lg p-6 max-w-md mx-auto text-left mb-8">
                            <p class="text-gray-700">Aplikasi Kasir Anda sedang disiapkan dan sudah terhubung dengan akun yang Anda gunakan saat login tadi.</p>
                        </div>
                    @endif

                    <div class="space-y-4">
                        <a href="http://127.0.0.1:8001/login" target="_blank" class="inline-block bg-indigo-600 text-white font-bold px-8 py-4 rounded-lg hover:bg-indigo-700 transition text-lg w-full sm:w-auto shadow-md">
                            Buka Aplikasi Kasir Sekarang
                        </a>
                        <br>
                        <a href="/" class="inline-block text-indigo-600 font-semibold hover:underline mt-4">
                            Kembali ke Beranda
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
