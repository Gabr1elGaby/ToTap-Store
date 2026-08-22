<?php
$file = 'resources/views/checkout/payment.blade.php';
$content = file_get_contents($file);

$oldBcaSection = <<<BLADE
                              <div class="flex items-center justify-between mb-4 pb-4 border-b">
                                  <span class="font-semibold text-gray-700">Bank BCA</span>
                                  <img src="https://upload.wikimedia.org/wikipedia/commons/5/5c/Bank_Central_Asia.svg" alt="BCA" class="h-6">
                              </div>
                              
                              <div class="mb-2">
                                  <p class="text-xs text-gray-500 mb-1 text-center">Nomor Virtual Account</p>
                                  <div class="flex items-center justify-between bg-gray-50 p-3 rounded border">
                                      <span class="text-xl font-bold tracking-widest text-indigo-700" id="va-number">8077 0123 4567 890</span>
                                      <button onclick="navigator.clipboard.writeText('807701234567890'); alert('Nomor disalin!')" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800">Salin</button>
                                  </div>
                              </div>
BLADE;

// Wait, I should check the exact HTML in the file first because I added copy-paste logic earlier maybe?
// Let's read the exact block first.
