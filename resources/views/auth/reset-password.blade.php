<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Atur Ulang Password</h2>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
            Silakan masukkan password baru untuk akun Anda.
        </p>
    </div>

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

    <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email</label>
            <input id="email" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm opacity-80" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly autocomplete="username" />
        </div>

        <!-- Nomor WhatsApp Terdaftar -->
        <div>
            <label for="phone_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp Terdaftar</label>
            <input id="phone_number" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="tel" name="phone_number" value="{{ old('phone_number') }}" required autofocus placeholder="Contoh: 081234567890" />
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Verifikasi keamanan: masukkan nomor WhatsApp yang terdaftar pada akun ini.</p>
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
            <input id="password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
            <input id="password_confirmation" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password baru" />
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md shadow-blue-500/20 text-sm">
                Simpan Password Baru
            </button>
        </div>

        <div class="text-center pt-2">
            <a href="/" class="text-xs text-gray-500 hover:text-blue-500 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </form>
</x-guest-layout>
