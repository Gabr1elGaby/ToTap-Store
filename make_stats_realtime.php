<?php

// 1. Update routes/web.php
$routeFile = 'routes/web.php';
$routeContent = file_get_contents($routeFile);

$oldRoute = <<<PHP
Route::get('/', function () {
    \$products = \App\Models\Product::where('is_active', true)->with(['plans' => function(\$q) {
        \$q->where('is_active', true)->orderBy('price');
    }])->get();

    return view('welcome', compact('products'));
});
PHP;

$newRoute = <<<PHP
Route::get('/', function () {
    \$products = \App\Models\Product::where('is_active', true)->with(['plans' => function(\$q) {
        \$q->where('is_active', true)->orderBy('price');
    }])->get();

    // REAL-TIME STATS
    \$totalUsers = \App\Models\User::count();
    
    // Sum of all paid transactions across all modules
    \$totalTransactions = 0;
    
    // Enterprise POS Orders
    if (class_exists(\App\Models\Order::class)) {
        \$totalTransactions += \App\Models\Order::whereIn('status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }
    
    // Top Up Transactions
    if (class_exists(\App\Models\TopupTransaction::class)) {
        \$totalTransactions += \App\Models\TopupTransaction::whereIn('payment_status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }
    
    // CV / General Transactions
    if (class_exists(\App\Models\Transaction::class)) {
        \$totalTransactions += \App\Models\Transaction::whereIn('status', ['paid', 'success', 'PAID', 'SUCCESS'])->count();
    }

    return view('welcome', compact('products', 'totalUsers', 'totalTransactions'));
});
PHP;

$routeContent = str_replace($oldRoute, $newRoute, $routeContent);
file_put_contents($routeFile, $routeContent);


// 2. Update welcome.blade.php
$welcomeFile = 'resources/views/welcome.blade.php';
$content = file_get_contents($welcomeFile);

$oldStats = <<<BLADE
        <!-- Stats Section -->
        <section class="border-b border-gray-800 bg-gray-900 py-12 relative z-20 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-gray-800">
                    <div class="p-4" data-aos="fade-up" data-aos-delay="100">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">99.9K<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Pengguna</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">50<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Layanan</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="300">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">2.5M<span class="text-blue-500">+</span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Transaksi</p>
                    </div>
                </div>
            </div>
        </section>
BLADE;

$newStats = <<<BLADE
        <!-- Stats Section -->
        <section class="border-b border-gray-800 bg-gray-900 py-12 relative z-20 shadow-xl">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center divide-y md:divide-y-0 md:divide-x divide-gray-800">
                    <div class="p-4" data-aos="fade-up" data-aos-delay="100">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">{{ number_format(\$totalUsers) }}<span class="text-blue-500"></span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Pengguna</p>
                    </div>
                    <div class="p-4" data-aos="fade-up" data-aos-delay="200">
                        <p class="text-4xl font-extrabold text-white mb-2" style="font-family: 'Orbitron', sans-serif;">{{ number_format(\$totalTransactions) }}<span class="text-blue-500"></span></p>
                        <p class="text-gray-400 text-sm font-semibold tracking-wider uppercase">Total Transaksi Berhasil</p>
                    </div>
                </div>
            </div>
        </section>
BLADE;

$content = str_replace($oldStats, $newStats, $content);
file_put_contents($welcomeFile, $content);

echo "Updated to Real-time DB stats.\n";
