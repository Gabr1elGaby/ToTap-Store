<section>
    <header class="border-b border-gray-100 dark:border-gray-700/60 pb-4 mb-6">
        <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-2.5">
            <i class="fas fa-key text-amber-500 text-xl"></i>
            {{ __('Perbarui Kata Sandi') }}
        </h2>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Kata Sandi Saat Ini') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                    <i class="fas fa-lock text-sm"></i>
                </div>
                <input id="update_password_current_password" 
                       name="current_password" 
                       type="password" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm" 
                       autocomplete="current-password" 
                       placeholder="Masukkan kata sandi lama" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Kata Sandi Baru') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                    <i class="fas fa-key text-sm"></i>
                </div>
                <input id="update_password_password" 
                       name="password" 
                       type="password" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm" 
                       autocomplete="new-password" 
                       placeholder="Minimal 8 karakter" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Konfirmasi Kata Sandi Baru') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                    <i class="fas fa-check-double text-sm"></i>
                </div>
                <input id="update_password_password_confirmation" 
                       name="password_confirmation" 
                       type="password" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition shadow-sm" 
                       autocomplete="new-password" 
                       placeholder="Ulangi kata sandi baru" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-lg shadow-amber-500/20 flex items-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> {{ __('Simpan Kata Sandi') }}
            </button>

            @if (session('status') === 'password-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20"
                >
                    <i class="fas fa-check-circle"></i> {{ __('Kata sandi berhasil diperbarui!') }}
                </div>
            @endif
        </div>
    </form>
</section>
