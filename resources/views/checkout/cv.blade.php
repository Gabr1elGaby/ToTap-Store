<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran CV - ToTap Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white rounded shadow-xl overflow-hidden">
        <div class="p-6 bg-blue-600 text-white text-center">
            <h1 class="text-2xl font-bold">ToTap Store Checkout</h1>
            <p class="text-blue-100 text-sm mt-1">Pembelian Template CV</p>
        </div>
        
        <div class="p-6">
            <div class="flex justify-between mb-4 pb-4 border-b">
                <span class="text-gray-600">Template</span>
                <span class="font-bold text-gray-900">{{ $cv->template_name }}</span>
            </div>
            <div class="flex justify-between mb-4 pb-4 border-b">
                <span class="text-gray-600">Nama Lengkap</span>
                <span class="font-bold text-gray-900">{{ $cv->name }}</span>
            </div>
            <div class="flex justify-between mb-6">
                <span class="text-gray-600 text-lg font-bold">Total Tagihan</span>
                <span class="text-blue-600 text-xl font-extrabold">Rp{{ number_format($cv->price, 0, ',', '.') }}</span>
            </div>

            <form action="{{ route('cv.payment.simulate', $cv->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded font-bold hover:bg-blue-700 transition shadow-md">
                    Simulasikan Pembayaran Berhasil
                </button>
            </form>
            
            <div class="text-center mt-4">
                <p class="text-xs text-gray-400">Gunakan metode pembayaran di bawah untuk mendapatkan file PDF CV Anda.</p>
            </div>
        </div>
    </div>
</body>
</html>
