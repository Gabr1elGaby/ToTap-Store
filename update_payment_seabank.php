<?php
$file = 'resources/views/checkout/payment.blade.php';
$content = file_get_contents($file);

// Find the block from "Bank BCA" to the closing div of the copy button
$pattern = '/<div class="flex items-center justify-between mb-4 pb-4 border-b">\s*<span class="font-semibold text-gray-700">Bank BCA<\/span>[\s\S]*?<\/div>\s*<\/div>/';

$replacement = <<<BLADE
<div class="flex items-center justify-between mb-4 pb-4 border-b">
                                  <span class="font-semibold text-gray-700">SeaBank</span>
                                  <span class="font-extrabold text-[#FF6600] text-xl italic tracking-tight">SeaBank</span>
                              </div>
                              
                              <div class="mb-2">
                                  <p class="text-xs text-gray-500 mb-1 text-center">Nomor Rekening</p>
                                  <div class="flex items-center justify-between bg-gray-50 p-3 rounded border">
                                      <span class="text-xl font-bold tracking-widest text-[#FF6600]" id="va-number">9010 8092 0263</span>
                                      <button type="button" onclick="navigator.clipboard.writeText('901080920263'); alert('Nomor Rekening berhasil disalin!')" class="text-sm font-semibold text-[#FF6600] hover:text-orange-800">Salin</button>
                                  </div>
                                  <p class="text-xs text-gray-500 mt-2 text-center">a.n. ToTap Store (Amelia)</p>
                              </div>
BLADE;

$newContent = preg_replace($pattern, $replacement, $content);
file_put_contents($file, $newContent);

if ($newContent !== $content) {
    echo "Successfully updated to SeaBank.\n";
} else {
    echo "Failed to update using preg_replace.\n";
}
