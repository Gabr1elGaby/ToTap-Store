<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$modalAndScript = <<<HTML
    <!-- Nickname Check Modal -->
    <div id="nickname-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-indigo-100 dark:bg-indigo-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900 dark:text-white" id="modal-title">
                                Konfirmasi Nickname
                            </h3>
                            <div class="mt-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-xl">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Nickname Anda:</p>
                                <p class="text-xl font-bold text-indigo-600 dark:text-indigo-400 mt-1" id="nickname-text">Memuat...</p>
                            </div>
                            <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                                Apakah ID dan Nickname ini sudah benar? Lanjutkan pembayaran jika sudah sesuai.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700/50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitForm()" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Ya, Lanjut Bayar
                    </button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-xl border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('topup-form');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Cegah submit langsung
            
            // Cek apakah produk dan ID sudah diisi
            const formData = new FormData(form);
            if (!formData.get('product_id')) {
                alert('Pilih nominal top up terlebih dahulu!');
                return;
            }
            if (!formData.get('player_id')) {
                alert('Masukkan ID Anda!');
                return;
            }
            
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = 'Mengecek ID...';
            submitBtn.disabled = true;
            
            // Lakukan pengecekan nickname
            fetch('{{ route("topup.check-nickname", \$game->slug) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                if (data.result === false) {
                    alert('GAGAL: ' + (data.message || 'ID tidak ditemukan atau tidak valid!'));
                } else if (data.data) {
                    // Berhasil, tampilkan modal konfirmasi
                    document.getElementById('nickname-text').innerText = data.data; // VIP Reseller biasanya mengembalikan string nickname di data
                    document.getElementById('nickname-modal').classList.remove('hidden');
                } else {
                    // Fallback kalau API sukses tapi format beda
                    document.getElementById('nickname-text').innerText = 'Tidak dapat memuat nickname, tapi ID valid.';
                    document.getElementById('nickname-modal').classList.remove('hidden');
                }
            })
            .catch(err => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                alert('Terjadi kesalahan saat mengecek ID.');
                console.error(err);
            });
        });
        
        function closeModal() {
            document.getElementById('nickname-modal').classList.add('hidden');
        }
        
        function submitForm() {
            // Bypass event listener dan submit langsung
            form.submit();
        }
    </script>
</x-app-layout>
HTML;

$content = str_replace('</x-app-layout>', $modalAndScript, $content);
file_put_contents($file, $content);
echo "Frontend interceptor injected.\n";
