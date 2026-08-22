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
                    {{-- Customer Rating & Feedback Box --}}
                    <div id="review-card" class="bg-indigo-50/50 dark:bg-gray-700/40 border border-indigo-100 dark:border-gray-600 rounded-2xl p-6 max-w-md mx-auto text-left mb-8 shadow-sm">
                        <div class="text-center mb-4">
                            <span class="text-xs uppercase tracking-wider font-bold text-indigo-600 dark:text-indigo-400">Kepuasan Pelanggan</span>
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mt-0.5">Beri Rating & Saran untuk Layanan Kami</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Masukan Anda sangat membantu kami meningkatkan kualitas layanan ToTap Store.</p>
                        </div>

                        <form id="softwareReviewForm" onsubmit="submitReview(event)" class="space-y-4">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->order_number }}">
                            <input type="hidden" name="order_type" value="software">
                            <input type="hidden" name="customer_name" value="{{ $order->user->name ?? ($order->customer_name ?? 'Pelanggan POS') }}">
                            <input type="hidden" name="customer_contact" value="{{ $order->user->email ?? ($order->customer_email ?? '') }}">
                            <input type="hidden" name="product_name" value="{{ $order->plan->name ?? 'Software Subscription' }}">
                            <input type="hidden" id="selected-rating" name="rating" value="5">

                            {{-- Star Picker --}}
                            <div class="flex flex-col items-center justify-center gap-1">
                                <div class="flex items-center gap-2 text-3xl cursor-pointer" id="star-container">
                                    <span class="star text-amber-400 transition transform hover:scale-125" onclick="setRating(1)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125" onclick="setRating(2)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125" onclick="setRating(3)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125" onclick="setRating(4)">★</span>
                                    <span class="star text-amber-400 transition transform hover:scale-125" onclick="setRating(5)">★</span>
                                </div>
                                <span id="rating-label" class="text-xs font-bold text-amber-500 mt-1">5/5 - Sangat Puas! ⭐</span>
                            </div>

                            {{-- Review Textarea --}}
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kritik & Saran:</label>
                                <textarea name="review_text" rows="3" placeholder="Tuliskan pengalaman, kritik, atau saran Anda..." class="w-full text-xs p-3 rounded-xl border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                            </div>

                            <button type="submit" id="submit-review-btn" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-700 hover:to-blue-700 text-white font-bold rounded-xl text-xs shadow-md transition">
                                Kirim Ulasan ⭐
                            </button>
                        </form>

                        <div id="review-success" class="hidden text-center py-4 space-y-2">
                            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 rounded-full flex items-center justify-center mx-auto text-xl font-bold">
                                ✓
                            </div>
                            <h5 class="text-sm font-bold text-gray-900 dark:text-white">Terima Kasih Atas Ulasan Anda!</h5>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Masukan Anda telah berhasil disimpan untuk evaluasi tim kami.</p>
                        </div>
                    </div>

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

    <script>
        const ratingLabels = {
            1: '1/5 - Sangat Buruk',
            2: '2/5 - Kurang Memuaskan',
            3: '3/5 - Cukup',
            4: '4/5 - Bagus & Cepat',
            5: '5/5 - Sangat Puas! ⭐'
        };

        function setRating(num) {
            document.getElementById('selected-rating').value = num;
            document.getElementById('rating-label').innerText = ratingLabels[num] || `${num}/5`;
            
            const stars = document.querySelectorAll('#star-container .star');
            stars.forEach((star, idx) => {
                if (idx < num) {
                    star.classList.remove('text-gray-300', 'dark:text-gray-600');
                    star.classList.add('text-amber-400');
                } else {
                    star.classList.remove('text-amber-400');
                    star.classList.add('text-gray-300', 'dark:text-gray-600');
                }
            });
        }

        function submitReview(e) {
            e.preventDefault();
            const form = document.getElementById('softwareReviewForm');
            const formData = new FormData(form);
            const btn = document.getElementById('submit-review-btn');
            btn.disabled = true;
            btn.innerText = 'Mengirim...';

            fetch('/api/reviews', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                form.classList.add('hidden');
                document.getElementById('review-success').classList.remove('hidden');
            })
            .catch(() => {
                form.classList.add('hidden');
                document.getElementById('review-success').classList.remove('hidden');
            });
        }
    </script>
</x-app-layout>
