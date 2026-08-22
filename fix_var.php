<?php
$file = 'resources/views/topup/show.blade.php';
$content = file_get_contents($file);

$oldCode = <<<BLADE
                                    @php
                                        // Group by category logic
                                        \$categories = ['Pass & Member', 'Item & Lainnya', 'Mata Uang Game'];
                                    @endphp

                                    @foreach(\$categories as \$cat)
                                        @php
                                            \$catProducts = \$products->filter(function(\$p) use (\$cat) {
                                                if (\$cat === 'Pass & Member') {
                                                    return stripos(\$p->name, 'pass') !== false || stripos(\$p->name, 'starlight') !== false || stripos(\$p->name, 'member') !== false || stripos(\$p->name, 'twilight') !== false;
                                                } elseif (\$cat === 'Item & Lainnya') {
                                                    return stripos(\$p->name, 'item') !== false || stripos(\$p->name, 'ticket') !== false || stripos(\$p->name, 'gem') !== false || stripos(\$p->name, 'crystal') !== false || stripos(\$p->name, 'coin') !== false;
                                                } else {
                                                    return stripos(\$p->name, 'pass') === false && stripos(\$p->name, 'starlight') === false && stripos(\$p->name, 'member') === false && stripos(\$p->name, 'twilight') === false && stripos(\$p->name, 'item') === false && stripos(\$p->name, 'ticket') === false && stripos(\$p->name, 'gem') === false && stripos(\$p->name, 'crystal') === false && stripos(\$p->name, 'coin') === false;
                                                }
                                            });
                                        @endphp
                                        
                                        @if(\$catProducts->count() > 0)
                                            <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-3 mt-6 border-b pb-2">{{ \$cat }}</h4>
                                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                                @foreach(\$catProducts as \$product)
                                                    <div @click="selectedProduct = '{{ \$product->id }}'" 
                                                         :class="selectedProduct == '{{ \$product->id }}' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-200 dark:border-gray-700'"
                                                         class="relative rounded-xl border-2 p-3 cursor-pointer hover:border-indigo-400 transition-all text-center">
                                                        <div class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1">{{ \$product->name }}</div>
                                                        <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
BLADE;

$newCode = <<<BLADE
                                    @foreach(\$categories as \$cat => \$catProducts)
                                        <h4 class="font-bold text-gray-700 dark:text-gray-300 mb-3 mt-6 border-b pb-2">{{ \$cat }}</h4>
                                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                            @foreach(\$catProducts as \$product)
                                                <div @click="selectedProduct = '{{ \$product->id }}'" 
                                                     :class="selectedProduct == '{{ \$product->id }}' ? 'border-indigo-600 bg-indigo-50 dark:bg-indigo-900/50' : 'border-gray-200 dark:border-gray-700'"
                                                     class="relative rounded-xl border-2 p-3 cursor-pointer hover:border-indigo-400 transition-all text-center">
                                                    <div class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-1">{{ \$product->name }}</div>
                                                    <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">Rp{{ number_format(\$product->price_sell, 0, ',', '.') }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
BLADE;

$content = str_replace($oldCode, $newCode, $content);
file_put_contents($file, $content);
echo "View fixed.\n";
