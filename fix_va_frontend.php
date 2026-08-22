<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$startMarker = '<label class="flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all" :class="selectedPayment == \'bank_transfer\'';
$startPos = strpos($content, $startMarker);

if ($startPos !== false) {
    $endMarker = '</label>';
    $endPos = strpos($content, $endMarker, $startPos);
    
    if ($endPos !== false) {
        $endPos += strlen($endMarker); // include the </label>
        
        $newHtml = <<<HTML
                                        <!-- BCA VA -->
                                        <label class="flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all" :class="selectedPayment == 'bca_va' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700'">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" name="payment_method" value="bca_va" x-model="selectedPayment" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BCA Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BCA</span>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-6">Transfer Bank Otomatis</div>
                                        </label>

                                        <!-- BNI VA -->
                                        <label class="flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all" :class="selectedPayment == 'bni_va' ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-gray-200 dark:border-gray-700'">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" name="payment_method" value="bni_va" x-model="selectedPayment" class="text-orange-500 focus:ring-orange-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BNI Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-orange-100 text-orange-800 rounded">BNI</span>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-6">Transfer Bank Otomatis</div>
                                        </label>

                                        <!-- BRI VA -->
                                        <label class="flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all" :class="selectedPayment == 'bri_va' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700'">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" name="payment_method" value="bri_va" x-model="selectedPayment" class="text-blue-500 focus:ring-blue-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BRI Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BRI</span>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-6">Transfer Bank Otomatis</div>
                                        </label>

                                        <!-- Mandiri VA -->
                                        <label class="flex flex-col p-4 border-2 rounded-xl cursor-pointer transition-all" :class="selectedPayment == 'mandiri_va' ? 'border-yellow-500 bg-yellow-50 dark:bg-yellow-900/20' : 'border-gray-200 dark:border-gray-700'">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    <input type="radio" name="payment_method" value="mandiri_va" x-model="selectedPayment" class="text-yellow-500 focus:ring-yellow-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">Mandiri Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">MANDIRI</span>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-6">Transfer Bank Otomatis</div>
                                        </label>
HTML;
        
        $content = substr_replace($content, $newHtml, $startPos, $endPos - $startPos);
        file_put_contents($file, $content);
        echo "Successfully injected exact UI.\n";
    }
} else {
    echo "Marker not found!\n";
}
