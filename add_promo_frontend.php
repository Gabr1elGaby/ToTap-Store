<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldCardContent = <<<HTML
                                                        <div class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs md:text-base mb-1">{{ \$product->_short_name ?? \$product->name }}</div>
                                                        <div class="text-indigo-600 dark:text-indigo-400 font-bold text-[11px] md:text-sm">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
HTML;

$newCardContent = <<<HTML
                                                        <!-- Label Promo -->
                                                        @if(\$product->is_promo)
                                                            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-[9px] md:text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse shadow-md">PROMO</div>
                                                        @endif
                                                        
                                                        <div class="font-bold text-gray-800 dark:text-gray-200 leading-tight text-xs md:text-base mb-1">{{ \$product->_short_name ?? \$product->name }}</div>
                                                        
                                                        <!-- Harga -->
                                                        <div class="flex flex-col items-center">
                                                            @if(\$product->is_promo && \$product->price_normal)
                                                                <div class="text-[9px] md:text-[10px] text-gray-400 line-through mb-0.5">Rp{{ number_format(\$product->price_normal, 0, ',', '.') }}</div>
                                                                <div class="text-red-500 dark:text-red-400 font-bold text-[11px] md:text-sm">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                                            @else
                                                                <div class="text-indigo-600 dark:text-indigo-400 font-bold text-[11px] md:text-sm">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                                            @endif
                                                        </div>
HTML;

$content = str_replace($oldCardContent, $newCardContent, $content);

// We need to add "relative" to the card div so the absolute promo label works.
$content = preg_replace('/class="cursor-pointer border-2 rounded-xl/i', 'class="relative cursor-pointer border-2 rounded-xl', $content);

file_put_contents($file, $content);
echo "Frontend promo added.\n";
