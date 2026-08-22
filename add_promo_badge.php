<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldCard = <<<HTML
                    @foreach(\$activeGames as \$game)
                    <a href="{{ route('topup.show', \$game->slug) }}" class="block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-full h-48 relative overflow-hidden bg-gray-200">
HTML;

$newCard = <<<HTML
                    @foreach(\$activeGames as \$game)
                    @php
                        \$hasPromo = \$game->products()->where('is_promo', true)->where('status', 'available')->exists();
                    @endphp
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                        @if(\$hasPromo)
                            <!-- BADGE PROMO -->
                            <div class="absolute z-10 top-2 right-2 bg-red-600 text-white text-[10px] md:text-xs font-extrabold px-3 py-1 rounded-full animate-pulse shadow-lg shadow-red-500/50">PROMO</div>
                        @endif
                        <div class="w-full h-48 relative overflow-hidden bg-gray-200">
HTML;

// Note: I added 'relative' to the <a> tag class and inserted the badge right inside it.

$content = str_replace($oldCard, $newCard, $content);
file_put_contents($file, $content);
echo "Promo badge added to homepage.\n";
