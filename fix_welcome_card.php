<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$pattern = '/@empty\s*<div class="col-span-full text-center text-gray-500">\s*Belum ada produk yang tersedia\.\s*<\/div>\s*@endforelse\s*<\/div>/s';

$newCard = <<<BLADE
                    @empty
                    <div class="col-span-full text-center text-gray-500">
                        Belum ada produk yang tersedia.
                    </div>
                    @endforelse
                    
                    <!-- TOP UP GAME HUB CARD -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded border border-gray-200 shadow-sm overflow-hidden flex flex-col transform hover:-translate-y-1 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                        <div class="p-8 flex-1 flex flex-col justify-center items-center text-center">
                            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mb-6 shadow-inner">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-3xl font-extrabold text-white mb-2" style="font-family: 'Righteous', cursive; letter-spacing: 2px;">GAMING CENTER</h3>
                            <p class="text-blue-100 text-sm mb-6">Pusat Layanan Top Up Diamond & Game Favorit (Mobile Legends, Valorant, Free Fire, PUBG, Roblox, dll)</p>
                        </div>
                        <div class="p-6 bg-gray-900/40 flex items-center justify-center backdrop-blur-sm">
                            @guest
                            <button @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="px-8 py-3 bg-white text-blue-700 font-bold rounded shadow hover:bg-gray-100 transition w-full text-center tracking-wide">
                                MASUK & TELUSURI GAME
                            </button>
                            @else
                            <a href="{{ route('topup.index') }}" class="px-8 py-3 bg-white text-blue-700 font-bold rounded shadow hover:bg-gray-100 transition w-full text-center tracking-wide">
                                TELUSURI GAME
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
BLADE;

$content = preg_replace($pattern, $newCard, $content);
file_put_contents($file, $content);
echo "Hub Card injected with preg_replace.\n";
