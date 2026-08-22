<?php
$file = 'resources/views/topup/checkout.blade.php';
$content = file_get_contents($file);

// 1. WIDEN THE CONTAINER
$content = str_replace('max-w-2xl', 'max-w-3xl', $content);

// 2. INJECT TUTORIAL
$oldHtml = <<<HTML
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Transfer tepat sesuai nominal hingga 3 digit terakhir.</p>
                    @else
HTML;

$newHtml = <<<HTML
                        </div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300 mb-6">Transfer tepat sesuai nominal hingga 3 digit terakhir.</p>
                        
                        <!-- TUTORIAL / INSTRUKSI PEMBAYARAN -->
                        <div class="text-left bg-white dark:bg-gray-800 rounded-xl p-5 shadow-sm border border-gray-100 dark:border-gray-700 mt-4">
                            <h4 class="font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" /></svg>
                                Cara Pembayaran
                            </h4>
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400">
                                @if(\$paymentData['bank'] === 'BCA')
                                    <li>Buka aplikasi <strong>BCA Mobile</strong> atau M-BCA.</li>
                                    <li>Pilih menu <strong>m-Transfer</strong> > <strong>BCA Virtual Account</strong>.</li>
                                @elseif(\$paymentData['bank'] === 'MANDIRI')
                                    <li>Buka aplikasi <strong>Livin' by Mandiri</strong>.</li>
                                    <li>Pilih menu <strong>Bayar</strong> > <strong>Multipayment / E-Commerce</strong>.</li>
                                @elseif(\$paymentData['bank'] === 'BNI')
                                    <li>Buka aplikasi <strong>BNI Mobile Banking</strong>.</li>
                                    <li>Pilih menu <strong>Transfer</strong> > <strong>Virtual Account Billing</strong>.</li>
                                @else
                                    <li>Buka aplikasi Mobile Banking atau ATM <strong>{{ \$paymentData['bank'] }}</strong> Anda.</li>
                                    <li>Pilih menu <strong>Transfer > Virtual Account</strong> (Atau Pembayaran VA/Briva).</li>
                                @endif
                                <li>Masukkan nomor Virtual Account di atas.</li>
                                <li>Pastikan nominal dan nama sesuai dengan pesanan ToTap Store.</li>
                                <li>Masukkan PIN Anda untuk menyelesaikan pembayaran.</li>
                            </ol>
                        </div>
                    @else
HTML;

$content = str_replace($oldHtml, $newHtml, $content);
file_put_contents($file, $content);
echo "Tutorial injected.\n";
