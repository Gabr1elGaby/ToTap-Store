<?php
$file = 'resources/views/welcome.blade.php';
$content = file_get_contents($file);

$oldLogic = <<<BLADE
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
BLADE;

$newLogic = <<<BLADE
                    @guest
                    <a href="#" @click.prevent="window.dispatchEvent(new CustomEvent('open-login'))" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    @else
                    <a href="{{ route('topup.show', \$game->slug) }}" class="relative block bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group" data-aos="fade-up" data-aos-delay="100">
                    @endauth
BLADE;

$content = str_replace($oldLogic, $newLogic, $content);

file_put_contents($file, $content);
echo "Homepage links intercepted for guests.\n";
