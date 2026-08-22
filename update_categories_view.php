<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldChunk = <<<HTML
                        <!-- Step 2: Pilih Nominal -->
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Nominal</h3>
                            </div>
                            
                            @if(\$products->isEmpty())
                                <div class="text-red-500 bg-red-50 p-4 rounded-xl">Produk belum tersedia.</div>
                            @else
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach(\$products as \$product)
                                    <div @click="selectedProduct = '{{ \$product->id }}'"
                                         class="cursor-pointer border-2 rounded-xl p-4 transition-all"
                                         :class="selectedProduct == '{{ \$product->id }}' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500'">
                                        <div class="font-bold text-gray-800 dark:text-gray-200 mb-1">{{ \$product->name }}</div>
                                        <div class="text-indigo-600 dark:text-indigo-400 font-bold text-sm">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
HTML;

$newChunk = <<<HTML
                        <!-- Step 2: Pilih Nominal -->
                        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold">2</div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Pilih Nominal</h3>
                            </div>
                            
                            @if(empty(\$categories))
                                <div class="text-red-500 bg-red-50 p-4 rounded-xl">Produk belum tersedia.</div>
                            @else
                                @foreach(\$categories as \$categoryName => \$catProducts)
                                    <div class="mb-6 last:mb-0">
                                        <div class="flex items-center gap-2 mb-3">
                                            <h4 class="font-bold text-gray-700 dark:text-gray-300">{{ \$categoryName }}</h4>
                                            <div class="h-px bg-gray-200 dark:bg-gray-700 flex-1"></div>
                                        </div>
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                            @foreach(\$catProducts as \$product)
                                            <div @click="selectedProduct = '{{ \$product->id }}'"
                                                class="cursor-pointer border-2 rounded-xl p-4 transition-all"
                                                :class="selectedProduct == '{{ \$product->id }}' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-200 dark:border-gray-700 hover:border-indigo-400 dark:hover:border-indigo-500'">
                                                <div class="font-bold text-gray-800 dark:text-gray-200 mb-1 leading-tight">{{ \$product->name }}</div>
                                                <div class="text-indigo-600 dark:text-indigo-400 font-bold text-sm">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
HTML;

$content = str_replace($oldChunk, $newChunk, $content);
file_put_contents($file, $content);
echo "View updated to use categories.\n";
