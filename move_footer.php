<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Move footer into form-container
$footer = <<<HTML
            <!-- Footer Buttons -->
            <div class="px-6 py-4 border-t border-gray-200 bg-white shrink-0 flex justify-between">
                <button @click="prevStep" :class="{'invisible': currentStep === 0}" class="px-4 py-2 border border-gray-300 rounded text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                    ← Sebelumnya
                </button>
                <button @click="nextStep" x-show="currentStep < steps.length - 1" class="px-6 py-2 bg-gray-900 rounded text-sm font-bold text-white hover:bg-black transition shadow-md">
                    Selanjutnya →
                </button>
                <button @click="checkout" x-show="currentStep === steps.length - 1" class="px-6 py-2 bg-blue-600 rounded text-sm font-bold text-white hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                    <span x-show="!loading">Selesai & Bayar</span>
                    <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
HTML;

// 1. Remove it from its current position
$content = str_replace($footer, "", $content);

// 2. Add it inside the end of #form-container
$footer_inside = <<<HTML

                <!-- Footer Buttons -->
                <div class="mt-8 pt-6 border-t border-gray-200 flex justify-between">
                    <button @click="prevStep" :class="{'invisible': currentStep === 0}" class="px-4 py-2 border border-gray-300 rounded text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        ← Sebelumnya
                    </button>
                    <button @click="nextStep" x-show="currentStep < steps.length - 1" class="px-6 py-2 bg-gray-900 rounded text-sm font-bold text-white hover:bg-black transition shadow-md">
                        Selanjutnya →
                    </button>
                    <button @click="checkout" x-show="currentStep === steps.length - 1" class="px-6 py-2 bg-blue-600 rounded text-sm font-bold text-white hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        <span x-show="!loading">Selesai & Bayar</span>
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </div>
HTML;

$content = str_replace('            </div>'."\n".'        </div>'."\n".'        <!-- Right Panel: Realtime Preview -->', $footer_inside."\n".'        </div>'."\n".'        <!-- Right Panel: Realtime Preview -->', $content);

file_put_contents($file, $content);
echo "Footer moved inside form container.\n";
