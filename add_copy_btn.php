<?php
$file = 'resources/views/topup/checkout.blade.php';
$content = file_get_contents($file);

$oldHtml = <<<HTML
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm mb-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Nomor Virtual Account ({{ \$paymentData['bank'] }})</p>
                            <p class="text-lg md:text-3xl w-full font-mono font-bold text-gray-900 dark:text-white tracking-wider break-all' style='word-break: break-all; white-space: normal;">{{ \$paymentData['va_number'] }}</p>
                        </div>
HTML;

$newHtml = <<<HTML
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm mb-4 relative group">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Nomor Virtual Account ({{ \$paymentData['bank'] }})</p>
                            
                            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                                <p id="va-text" class="text-xl sm:text-3xl font-mono font-bold text-gray-900 dark:text-white tracking-wider break-all" style="word-break: break-all;">{{ \$paymentData['va_number'] }}</p>
                                
                                <button onclick="copyVA()" id="copy-btn" class="flex items-center gap-2 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-800 text-indigo-600 dark:text-indigo-400 rounded-lg font-bold text-sm transition-all shadow-sm border border-indigo-100 dark:border-indigo-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                    <span id="copy-text">Salin</span>
                                </button>
                            </div>
                        </div>
HTML;

$content = str_replace($oldHtml, $newHtml, $content);

$jsToInject = <<<HTML
        // Fungsi Salin VA
        function copyVA() {
            const vaNumber = document.getElementById('va-text').innerText;
            navigator.clipboard.writeText(vaNumber).then(() => {
                const copyBtn = document.getElementById('copy-btn');
                const copyText = document.getElementById('copy-text');
                
                // Ubah tampilan sesaat
                copyBtn.classList.replace('bg-indigo-50', 'bg-green-100');
                copyBtn.classList.replace('text-indigo-600', 'text-green-700');
                copyText.innerText = 'Tersalin!';
                
                setTimeout(() => {
                    copyBtn.classList.replace('bg-green-100', 'bg-indigo-50');
                    copyBtn.classList.replace('text-green-700', 'text-indigo-600');
                    copyText.innerText = 'Salin';
                }, 2000);
            }).catch(err => {
                alert('Gagal menyalin: ' + err);
            });
        }
    </script>
</x-app-layout>
HTML;

$content = str_replace('    </script>'."\n".'</x-app-layout>', $jsToInject, $content);

file_put_contents($file, $content);
echo "Copy button added.\n";
