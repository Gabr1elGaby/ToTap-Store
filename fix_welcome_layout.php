<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

// 1. Tambahkan card "Top Up Game" ke dalam grid Katalog Sistem
$catalogGridClose = "                      @empty\n                          <p class=\"text-gray-500\">Belum ada produk lisensi yang tersedia.</p>\n                      @endforelse\n                  </div>";

$newCard = <<<BLADE
                      @empty
                          <p class="text-gray-500">Belum ada produk lisensi yang tersedia.</p>
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
                              <button @click="window.dispatchEvent(new CustomEvent('open-login'))" class="px-8 py-3 bg-white text-blue-700 font-bold rounded shadow hover:bg-gray-100 transition w-full text-center tracking-wide">
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

$content = str_replace($catalogGridClose, $newCard, $content);

// 2. Hapus seluruh section Top Up Game Termurah yang lama (grid 5 item)
$pattern = '/<!-- Top Up Games Section -->.*?<\/section>/s';
$content = preg_replace($pattern, '', $content);

// Update grid cols dari md:grid-cols-2 menjadi lg:grid-cols-3 agar muat 3 card
$content = str_replace('<div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">', '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto">', $content);

file_put_contents($file, $content);
echo "Welcome page updated with Hub Card.\n";
