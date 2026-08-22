<?php
$file = 'resources/views/software/index.blade.php';
$content = file_get_contents($file);

$dynamicHtml = <<<BLADE
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                @foreach(\$softwareProducts as \$product)
                <!-- Card -->
                <div class="bg-gray-800 rounded-lg border border-gray-700 shadow flex flex-col p-8 relative overflow-hidden">
                    @php
                        \$bestPlan = \$product->plans->first();
                        \$discountPercent = 0;
                        if(\$bestPlan && \$bestPlan->price_normal > 0 && \$bestPlan->price_normal > \$bestPlan->price) {
                            \$discountPercent = round(((\$bestPlan->price_normal - \$bestPlan->price) / \$bestPlan->price_normal) * 100);
                        }
                    @endphp
                    
                    @if(\$discountPercent > 0)
                    <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                        Diskon {{ \$discountPercent }}%
                    </div>
                    @endif

                    <h3 class="text-xl font-bold text-white mb-2">{{ \$product->name }}</h3>
                    <p class="text-sm text-gray-400 mb-6">{{ \$product->description }}</p>
                    
                    <ul class="space-y-3 mb-8 flex-1">
                        @if(\$product->features)
                            @foreach(is_array(\$product->features) ? \$product->features : json_decode(\$product->features, true) as \$feature)
                            <li class="flex items-start">
                                <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span class="text-sm text-gray-300">{{ \$feature }}</span>
                            </li>
                            @endforeach
                        @else
                            <li class="flex items-start"><span class="text-sm text-gray-500 italic">Fitur segera hadir...</span></li>
                        @endif
                    </ul>

                    <div class="mt-auto border-t border-gray-700 pt-6 flex justify-between items-center">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wider mb-1">
                                {{ \$product->slug === 'sistem-kasir-pos' ? 'LISENSI BULANAN' : 'HARGA MULAI' }}
                            </p>
                            @if(\$bestPlan)
                                @if(\$bestPlan->price_normal > 0 && \$bestPlan->price_normal > \$bestPlan->price)
                                    <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format(\$bestPlan->price_normal, 0, ',', '.') }}</div>
                                @endif
                                <p class="text-3xl font-bold text-white">Rp {{ number_format(\$bestPlan->price, 0, ',', '.') }}</p>
                            @else
                                <p class="text-xl font-bold text-white">Belum tersedia</p>
                            @endif
                        </div>
                        <a href="{{ \$product->slug === 'sistem-kasir-pos' ? '/produk/sistem-kasir-pos' : '/cv' }}" class="bg-blue-600 hover:bg-blue-700 text-white rounded font-bold transition text-sm shadow-md" style="padding: 10px 24px; letter-spacing: 0.5px;">Beli Layanan</a>
                    </div>
                </div>
                @endforeach
            </div>
BLADE;

// Replace the old hardcoded grid with the dynamic one
$content = preg_replace(
    '/<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">[\s\S]*?<\/div>\s*<\/div>\s*<\/div>/',
    $dynamicHtml . "\n        </div>\n    </div>",
    $content
);

file_put_contents($file, $content);
echo "Updated software index blade.\n";
