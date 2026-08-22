<?php
$file = 'resources/views/topup/checkout.blade.php';
$content = file_get_contents($file);

$oldList = <<<HTML
                            <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600 dark:text-gray-400 w-full whitespace-normal">
                                @if(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'BCA')
                                    <li>Buka aplikasi <strong>BCA Mobile</strong> atau M-BCA.</li>
                                    <li>Pilih menu <strong>m-Transfer</strong> > <strong>BCA Virtual Account</strong>.</li>
                                @elseif(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'MANDIRI')
                                    <li>Buka aplikasi <strong>Livin' by Mandiri</strong>.</li>
                                    <li>Pilih menu <strong>Bayar</strong> > <strong>Multipayment / E-Commerce</strong>.</li>
                                @elseif(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'BNI')
                                    <li>Buka aplikasi <strong>BNI Mobile Banking</strong>.</li>
                                    <li>Pilih menu <strong>Transfer</strong> > <strong>Virtual Account Billing</strong>.</li>
                                @else
                                    <li>Buka aplikasi Mobile Banking atau ATM <strong>{{ \$paymentData['bank'] ?? 'Anda' }}</strong>.</li>
                                    <li>Pilih menu <strong>Transfer > Virtual Account</strong> (Atau Pembayaran VA/Briva).</li>
                                @endif
                                <li>Masukkan nomor Virtual Account di atas.</li>
                                <li>Pastikan nominal dan nama sesuai dengan pesanan ToTap Store.</li>
                                <li>Masukkan PIN Anda untuk menyelesaikan pembayaran.</li>
                            </ol>
HTML;

$newList = <<<HTML
                            <div class="space-y-3 text-sm text-gray-600 dark:text-gray-400 w-full whitespace-normal">
                                @if(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'BCA')
                                    <p><strong>1.</strong> Buka aplikasi <strong>BCA Mobile</strong> atau M-BCA.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>m-Transfer</strong> > <strong>BCA Virtual Account</strong>.</p>
                                @elseif(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'MANDIRI')
                                    <p><strong>1.</strong> Buka aplikasi <strong>Livin' by Mandiri</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Bayar</strong> > <strong>Multipayment / E-Commerce</strong>.</p>
                                @elseif(isset(\$paymentData['bank']) && \$paymentData['bank'] === 'BNI')
                                    <p><strong>1.</strong> Buka aplikasi <strong>BNI Mobile Banking</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Transfer</strong> > <strong>Virtual Account Billing</strong>.</p>
                                @else
                                    <p><strong>1.</strong> Buka aplikasi Mobile Banking atau ATM <strong>{{ \$paymentData['bank'] ?? 'Anda' }}</strong>.</p>
                                    <p><strong>2.</strong> Pilih menu <strong>Transfer > Virtual Account</strong> (Atau Pembayaran VA/Briva).</p>
                                @endif
                                <p><strong>3.</strong> Masukkan nomor Virtual Account di atas.</p>
                                <p><strong>4.</strong> Pastikan nominal dan nama sesuai dengan pesanan ToTap Store.</p>
                                <p><strong>5.</strong> Masukkan PIN Anda untuk menyelesaikan pembayaran.</p>
                            </div>
HTML;

$content = str_replace($oldList, $newList, $content);
file_put_contents($file, $content);
echo "Numbers hardcoded.\n";
