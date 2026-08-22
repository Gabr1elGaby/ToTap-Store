<?php
$file = 'resources/views/products/show.blade.php';
$content = file_get_contents($file);

$oldCard = <<<BLADE
                      @forelse (\$product->plans as \$plan)
                          <div class="bg-gray-800 border-gray-700 border rounded-lg p-8 shadow-sm hover:shadow-lg transition flex flex-col">
                              <h3 class="text-2xl font-bold text-white mb-4 text-center">{{ strtoupper(\$plan->name) }}</h3>
                              <div class="text-center mb-6">
                                  <span class="text-4xl font-extrabold">Rp {{ number_format(\$plan->price, 0, ',', '.') }}</span>
                                  <span class="text-gray-400">/ {{ \$plan->duration_days }} hari</span>
                              </div>
BLADE;

$newCard = <<<BLADE
                      @forelse (\$product->plans as \$plan)
                          @php
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

$content = str_replace($oldCard, $newCard, $content);
file_put_contents($file, $content);
echo "Updated products/show.blade.php\n";
