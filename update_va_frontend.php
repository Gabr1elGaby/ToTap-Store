<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldHtml = <<<HTML
                                        <div class="p-4 rounded-xl border-2 transition-all cursor-pointer flex flex-col"
                                            :class="selectedPayment === 'bank_transfer' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-gray-600'"
                                            @click="selectedPayment = 'bank_transfer'">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment_method" value="bank_transfer" x-model="selectedPayment" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BCA</span>
                                            </div>
                                            <div class="text-xs text-gray-500 ml-6">Transfer Bank Otomatis</div>
                                        </label>
HTML;

$newHtml = <<<HTML
                                        <!-- BCA VA -->
                                        <div class="p-4 rounded-xl border-2 transition-all cursor-pointer flex flex-col"
                                            :class="selectedPayment === 'bca_va' ? 'border-indigo-600 bg-indigo-50/50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-gray-600'"
                                            @click="selectedPayment = 'bca_va'">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment_method" value="bca_va" x-model="selectedPayment" class="text-indigo-600 focus:ring-indigo-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BCA Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BCA</span>
                                            </div>
                                        </div>
                                        
                                        <!-- BNI VA -->
                                        <div class="p-4 rounded-xl border-2 transition-all cursor-pointer flex flex-col"
                                            :class="selectedPayment === 'bni_va' ? 'border-orange-500 bg-orange-50/50 dark:bg-orange-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-orange-300 dark:hover:border-gray-600'"
                                            @click="selectedPayment = 'bni_va'">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment_method" value="bni_va" x-model="selectedPayment" class="text-orange-500 focus:ring-orange-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BNI Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-orange-100 text-orange-800 rounded">BNI</span>
                                            </div>
                                        </div>

                                        <!-- BRI VA -->
                                        <div class="p-4 rounded-xl border-2 transition-all cursor-pointer flex flex-col"
                                            :class="selectedPayment === 'bri_va' ? 'border-blue-500 bg-blue-50/50 dark:bg-blue-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-blue-300 dark:hover:border-gray-600'"
                                            @click="selectedPayment = 'bri_va'">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment_method" value="bri_va" x-model="selectedPayment" class="text-blue-500 focus:ring-blue-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">BRI Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-blue-100 text-blue-800 rounded">BRI</span>
                                            </div>
                                        </div>

                                        <!-- Mandiri VA -->
                                        <div class="p-4 rounded-xl border-2 transition-all cursor-pointer flex flex-col"
                                            :class="selectedPayment === 'mandiri_va' ? 'border-yellow-500 bg-yellow-50/50 dark:bg-yellow-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-yellow-300 dark:hover:border-gray-600'"
                                            @click="selectedPayment = 'mandiri_va'">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center gap-3">
                                                    <input type="radio" name="payment_method" value="mandiri_va" x-model="selectedPayment" class="text-yellow-500 focus:ring-yellow-500">
                                                    <span class="font-bold text-gray-800 dark:text-gray-200">Mandiri Virtual Account</span>
                                                </div>
                                                <span class="text-xs font-bold px-2 py-1 bg-yellow-100 text-yellow-800 rounded">MANDIRI</span>
                                            </div>
                                        </div>
HTML;

$content = str_replace($oldHtml, $newHtml, $content);

// Ensure </label> is not dangling if I replaced it, wait, $oldHtml ended with </label> but $newHtml doesn't.
// Let's replace </label> properly if needed. My oldHtml included </label>. My newHtml doesn't have dangling labels.
file_put_contents($file, $content);
echo "Frontend VA UI updated.\n";
