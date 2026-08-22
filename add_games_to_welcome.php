<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$gamesCode = <<<HTML
        <!-- Top Up Games Section -->
        <section id="topup" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16" data-aos="fade-up">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Top Up Game Termurah</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Top up otomatis 24 jam untuk game favorit Anda.</p>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                    @php
                        \$activeGames = \App\Models\Game::where('is_active', true)->get();
                    @endphp
                    @foreach(\$activeGames as \$game)
                    <a href="{{ route('topup.show', \$game->slug) }}" class="block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        <div class="relative pt-[100%] overflow-hidden bg-gray-200">
                            @if(\$game->thumbnail)
                            <img src="{{ \$game->thumbnail }}" alt="{{ \$game->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                            <div class="absolute inset-0 w-full h-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-2xl">{{ substr(\$game->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-bold text-gray-900 truncate">{{ \$game->name }}</h3>
                            <p class="text-xs text-indigo-600 font-semibold mt-1">Beli Sekarang</p>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </section>
HTML;

// Insert right before <!-- Products Section -->
$content = str_replace('<!-- Products Section -->', $gamesCode . "\n\n        <!-- Products Section -->", $content);

file_put_contents($file, $content);
echo "Added Top Up section to welcome page.\n";
