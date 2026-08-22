<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

$pattern = '/(@forelse \(\$product->plans as \$plan\)\s*)<div class="bg-gray-800 border-gray-700 border rounded-lg p-8 shadow-sm hover:shadow-lg transition flex flex-col">\s*<h3 class="text-2xl font-bold text-white mb-4 text-center">\{\{ strtoupper\(\$plan->name\) \}\}<\/h3>\s*<div class="text-center mb-6">\s*<span class="text-4xl font-extrabold">Rp \{\{ number_format\(\$plan->price, 0, \',\', \'\.\'\) \}\}<\/span>\s*<span class="text-gray-400">\/ \{\{ \$plan->duration_days \}\} hari<\/span>\s*<\/div>/';

$replacement = <<<BLADE
$1@php
                              \$discountPercent = 0;
                              if(\$plan->price_normal > 0 && \$plan->price_normal > \$plan->price) {
                                  \$discountPercent = round(((\$plan->price_normal - \$plan->price) / \$plan->price_normal) * 100);
                              }
                          @endphp
                          <div class="bg-gray-800 border-gray-700 border rounded-lg p-8 shadow-sm hover:shadow-lg transition flex flex-col relative overflow-hidden">
                              @if(\$discountPercent > 0)
                              <div class="absolute top-0 right-0 bg-red-600 text-white font-black text-[10px] px-3 py-1 uppercase tracking-wider z-20" style="border-bottom-left-radius: 12px; box-shadow: -2px 2px 5px rgba(0,0,0,0.3);">
                                  Diskon {{ \$discountPercent }}%
                              </div>
                              @endif
                              
                              <h3 class="text-2xl font-bold text-white mb-4 text-center">{{ strtoupper(\$plan->name) }}</h3>
                              <div class="text-center mb-6">
                                  @if(\$plan->price_normal > 0 && \$plan->price_normal > \$plan->price)
                                      <div class="text-sm text-gray-500 line-through mb-1">Rp {{ number_format(\$plan->price_normal, 0, ',', '.') }}</div>
                                  @endif
                                  <span class="text-4xl font-extrabold text-white">Rp {{ number_format(\$plan->price, 0, ',', '.') }}</span>
                                  <span class="text-gray-400">/ {{ \$plan->duration_days == 0 ? 'Selamanya' : \$plan->duration_days . ' hari' }}</span>
                              </div>
BLADE;

$newContent = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $newContent);

if ($newContent !== $content) {
    echo "Successfully updated.\n";
} else {
    echo "Failed to update using preg_replace.\n";
}
