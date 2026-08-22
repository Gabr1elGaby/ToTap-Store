<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Simulasi Pembayaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900 dark:text-gray-100 text-center">
                    <h3 class="text-2xl font-bold mb-6">Selesaikan Pembayaran Anda</h3>
                    
                    <div class="border rounded-lg p-6 mb-8 max-w-md mx-auto" x-data="{ method: 'qris' }">
                        <p class="text-gray-500 mb-2">Order Number</p>
                        <p class="text-xl font-bold mb-4">{{ $order->order_number }}</p>

                        <p class="text-gray-500 mb-2">Total Tagihan</p>
                        <p class="text-3xl font-extrabold text-indigo-600 mb-6">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>

                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200 text-left mb-6">
                            <h4 class="font-bold text-gray-800 mb-3">Pilih Metode Pembayaran:</h4>
                            <div class="flex flex-col space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="qris" class="form-radio text-indigo-600 h-5 w-5" x-model="method">
                                    <span class="font-medium text-gray-700">QRIS (OVO, GoPay, Dana, dll)</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio" name="payment_method" value="transfer" class="form-radio text-indigo-600 h-5 w-5" x-model="method">
                                    <span class="font-medium text-gray-700">Transfer Bank (Virtual Account)</span>
                                </label>
                            </div>
                        </div>

                        <!-- QRIS Display -->
                        <div x-show="method === 'qris'" class="mb-8 flex flex-col items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded-lg">
                            <p class="text-sm font-bold text-gray-600 mb-4 uppercase tracking-wider">Scan QR Code Ini</p>
                            <!-- Mock QR Code using SVG -->
                            <svg class="w-48 h-48 text-gray-800" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm10-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm14-2h-4v2h2v2h-2v2h2v-2h2v-4zm-4 4h-2v2h2v-2zm-2-2h-2v2h2v-2zm2-2h-2v2h2v-2zm-6 2h2v2h-2v-2zm0-2h2v2h-2v-2zm-4 0h2v2H7v-2z"/>
                            </svg>
                            <p class="mt-4 text-xs text-gray-500 text-center">Buka aplikasi e-wallet atau m-banking Anda,<br>lalu scan QR Code di atas.</p>
                        </div>

                        <!-- Transfer Bank Display -->
                        <div x-show="method === 'transfer'" style="display: none;" class="mb-8 p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <p class="text-sm font-bold text-gray-600 mb-4 uppercase tracking-wider text-center">Transfer ke Virtual Account</p>
                            
                            <div class="flex items-center justify-between mb-4 pb-4 border-b">
                                  <span class="font-semibold text-gray-700">SeaBank</span>
                                  <span class="font-extrabold text-orange-600" style="color: #FF6600; text-xl italic tracking-tight">SeaBank</span>
                              </div>
                              
                              <div class="mb-2">
                                  <p class="text-xs text-gray-500 mb-1 text-center">Nomor Rekening</p>
                                  <div class="flex items-center justify-between bg-gray-50 p-3 rounded border">
                                      <span class="text-xl font-bold tracking-widest text-orange-600" style="color: #FF6600;" id="va-number">9010 8092 0263</span>
                                      <button type="button" onclick="navigator.clipboard.writeText('901080920263'); alert('Nomor Rekening berhasil disalin!')" class="text-sm font-semibold text-orange-600" style="color: #FF6600; hover:text-orange-800">Salin</button>
                                  </div>
                                  <p class="text-sm text-gray-800 mt-2 text-center"><strong>a.n. Gabriel</strong></p>
                              </div>
                            
                            <p class="text-xs text-gray-500 mt-4 text-center">Proses verifikasi pembayaran otomatis memakan waktu 1-5 menit.</p>
                        </div>

                        <form action="{{ route('payment.simulate', $order->order_number) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 text-white font-bold py-4 rounded-xl hover:bg-green-700 hover:shadow-lg transition-all text-lg flex justify-center items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Simulasikan Bayar Sukses
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
