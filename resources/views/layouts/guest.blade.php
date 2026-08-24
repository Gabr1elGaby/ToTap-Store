<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        
        <!-- Early Theme Initialization -->
        <script>
            if (localStorage.getItem('totap_theme') === 'light') {
                document.documentElement.classList.remove('dark');
                document.documentElement.style.backgroundColor = '#f8fafc';
            } else {
                document.documentElement.classList.add('dark');
                document.documentElement.style.backgroundColor = '#111827';
            }
        </script>

        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-white bg-slate-50 dark:bg-gray-900 transition-colors duration-200">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50 dark:bg-gray-900 transition-colors duration-200">
            <div class="mb-4">
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('images/logo-totap-v2.png') }}" class="h-12 w-auto object-contain drop-shadow-md" alt="Logo">
                    <span class="text-2xl font-bold tracking-widest text-gray-900 dark:text-white" style="font-family: 'Righteous', cursive;">TOTAP STORE</span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-6 py-6 bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
