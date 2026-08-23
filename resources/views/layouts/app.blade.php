<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" style="background-color: #111827;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <link href="https://fonts.googleapis.com/css2?family=Righteous&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&display=swap" rel="stylesheet">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    <style>
        .nav-item-glow {
            border-bottom: 2px solid transparent;
            transition: all 0.3s ease;
        }
        .nav-item-glow:hover {
            color: #60A5FA !important;
            border-bottom-color: #60A5FA !important;
        }
    </style>
</head>
    <body class="font-sans antialiased text-white bg-gray-900" x-data="{ showLogin: false, showRegister: false }" @open-login.window="showLogin = true" @open-register.window="showRegister = true" :class="{ 'overflow-hidden': showLogin || showRegister }">
        <div class="min-h-screen bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <x-auth-modals />
</body>
</html>
