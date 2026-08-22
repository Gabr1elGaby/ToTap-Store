<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Replace navbar
$old_nav = <<<'HTML'
    <nav class="bg-white border-b border-gray-200 shrink-0 shadow-sm z-10 relative">
        <div class="px-4 sm:px-6 lg:px-8 h-14 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <a href="{{ route('cv.index') }}" class="text-gray-500 hover:text-gray-900 transition flex items-center gap-1 text-sm font-semibold">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali
                </a>
                <span class="text-gray-300 mx-2">|</span>
                <span class="font-bold text-gray-900 text-sm">Template: {{ $template->name }}</span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium text-gray-500">Harga: <strong class="text-gray-900">Rp{{ number_format($template->price, 0, ',', '.') }}</strong></span>
                <button @click="checkout" :disabled="loading" class="bg-blue-600 text-white px-4 py-1.5 rounded text-sm font-bold shadow-sm hover:bg-blue-700 transition disabled:opacity-50">
                    <span x-show="!loading">Lanjut Pembayaran</span>
                    <span x-show="loading">Memproses...</span>
                </button>
            </div>
        </div>
    </nav>
HTML;

$new_nav = <<<'HTML'
    <nav class="bg-white border-b border-gray-200 shrink-0 shadow-sm z-10 relative">
        <div class="px-3 sm:px-6 h-14 flex justify-between items-center">
            <div class="flex items-center gap-1 sm:gap-2">
                <a href="{{ route('cv.index') }}" class="text-gray-500 hover:text-gray-900 transition flex items-center gap-1 text-xs sm:text-sm font-semibold shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                <span class="text-gray-300 mx-1 sm:mx-2 hidden md:inline">|</span>
                <span class="font-bold text-gray-900 text-sm hidden md:inline truncate">Template: {{ $template->name }}</span>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <span class="text-xs sm:text-sm font-medium text-gray-500">Harga: <strong class="text-gray-900">Rp{{ number_format($template->price, 0, ',', '.') }}</strong></span>
                <button @click="checkout" :disabled="loading" class="bg-blue-600 text-white px-3 sm:px-4 py-1.5 rounded text-xs sm:text-sm font-bold shadow-sm hover:bg-blue-700 transition disabled:opacity-50 shrink-0">
                    <span x-show="!loading">Pembayaran</span>
                    <span x-show="loading">...</span>
                </button>
            </div>
        </div>
    </nav>
HTML;

$content = str_replace($old_nav, $new_nav, $content);

// Add mobile preview button and modal logic
// Find x-data init to add showMobilePreview: false
$content = str_replace("previewLoading: false,", "previewLoading: false, showMobilePreview: false,", $content);

// Add Mobile Preview Button at bottom right corner (fixed)
$mobile_btn = <<<'HTML'
    <button @click="showMobilePreview = true" class="md:hidden fixed bottom-6 right-6 bg-gray-900 text-white rounded-full p-4 shadow-2xl z-40 hover:bg-gray-800 transition flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
    </button>
HTML;

// Find closing tag of form-container to insert the button
$content = str_replace("<!-- Right Panel: Preview (Desktop Only) -->", $mobile_btn . "\n        <!-- Right Panel: Preview (Desktop Only) -->", $content);

// Modify Right Panel to become a modal on mobile
$old_right_panel = <<<'HTML'
        <!-- Right Panel: Preview (Desktop Only) -->
        <div class="flex-1 bg-gray-800 p-4 md:p-8 overflow-y-auto hidden md:flex flex-col items-center justify-start relative">
            <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                REAL-TIME PREVIEW (Kertas A4)
            </div>
            
            <!-- A4 Paper Container -->
            <div class="w-[794px] h-[1123px] bg-white shadow-2xl relative shrink-0 overflow-y-auto transform scale-[0.6] xl:scale-75 origin-top" id="pdf-preview-container">
HTML;

$new_right_panel = <<<'HTML'
        <!-- Right Panel: Preview -->
        <div :class="{'hidden md:flex': !showMobilePreview, 'flex fixed inset-0 z-50': showMobilePreview}" class="flex-1 bg-gray-800 p-4 md:p-8 overflow-y-auto flex-col items-center justify-start relative">
            
            <!-- Mobile Close Button -->
            <button x-show="showMobilePreview" @click="showMobilePreview = false" class="md:hidden absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-6 flex items-center gap-2 mt-4 md:mt-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                REAL-TIME PREVIEW (Kertas A4)
            </div>
            
            <!-- A4 Paper Container -->
            <div class="w-[794px] h-[1123px] bg-white shadow-2xl relative shrink-0 overflow-y-auto transform scale-[0.4] sm:scale-[0.5] md:scale-[0.6] xl:scale-75 origin-top mt-[-100px] md:mt-0" id="pdf-preview-container">
HTML;

$content = str_replace($old_right_panel, $new_right_panel, $content);

file_put_contents($file, $content);
echo "Done adding mobile preview.\n";
