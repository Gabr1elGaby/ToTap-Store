<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Lupa Password</h2>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
            Masukkan email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password baru.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded-xl text-xs text-green-600 dark:text-green-400 font-medium">
            {{ session('status') }}
        </div>
    @endif

    <!-- Error Summary if any -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-red-500/10 border border-red-500/30 rounded-xl text-xs text-red-500 font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email Terdaftar</label>
            <input id="email" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md shadow-blue-500/20 text-sm">
                Kirim Link Reset Password
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="/" class="text-xs text-gray-500 hover:text-blue-500 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </form>
</x-guest-layout>
