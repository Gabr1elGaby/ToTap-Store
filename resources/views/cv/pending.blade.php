<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menunggu Pembayaran - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden p-8 text-center border border-gray-100">
        <a href="{{ url('/') }}" class="inline-block mb-6">
            <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-12 w-auto mx-auto object-contain drop-shadow-md">
        </a>
        <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Menunggu Pembayaran</h2>
        <p class="text-gray-600 mb-8">CV Anda sudah tersimpan dengan aman, namun Anda perlu menyelesaikan pembayaran untuk mengunduh PDF.</p>
        
        <a href="{{ route('cv.checkout.show', $cv->id) }}" class="block w-full bg-blue-600 text-white font-bold py-3 px-4 rounded shadow hover:bg-blue-700 transition mb-4">
            Lanjutkan Pembayaran
        </a>
        <a href="{{ route('cv.index') }}" class="block w-full bg-gray-100 text-gray-700 font-bold py-3 px-4 rounded hover:bg-gray-200 transition">
            Kembali ke Beranda CV
        </a>
    </div>
</body>
</html>
