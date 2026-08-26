<section>
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-100 dark:border-gray-700/60 pb-4 mb-6">
        <div>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-2.5">
                <i class="fas fa-user-circle text-blue-500 text-2xl"></i>
                {{ __('Informasi Akun & Kontak') }}
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
                {{ __('Perbarui data profil, alamat email, dan nomor WhatsApp/HP aktif akun Anda.') }}
            </p>
        </div>
        <div>
            @if(strtolower($user->role ?? '') === 'superadmin' || strtolower($user->role ?? '') === 'admin')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-indigo-500/10 border border-indigo-500/30 text-indigo-600 dark:text-indigo-400">
                    <i class="fas fa-shield-alt"></i> {{ $user->role ?? 'Super Admin' }}
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-blue-500/10 border border-blue-500/30 text-blue-600 dark:text-blue-400">
                    <i class="fas fa-user"></i> {{ $user->role ?? 'Customer' }}
                </span>
            @endif
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                    <i class="fas fa-id-badge text-sm"></i>
                </div>
                <input id="name" 
                       name="name" 
                       type="text" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                       value="{{ old('name', $user->name) }}" 
                       required 
                       autofocus 
                       autocomplete="name" 
                       placeholder="Contoh: Gabriel Gaby" />
            </div>
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Alamat Email') }} <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 dark:text-gray-500">
                    <i class="fas fa-envelope text-sm"></i>
                </div>
                <input id="email" 
                       name="email" 
                       type="email" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                       value="{{ old('email', $user->email) }}" 
                       required 
                       autocomplete="username" 
                       placeholder="nama@email.com" />
            </div>
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                    <p class="text-xs text-amber-700 dark:text-amber-300 font-medium">
                        {{ __('Alamat email Anda belum terverifikasi.') }}

                        <button form="send-verification" class="underline font-bold text-amber-800 dark:text-amber-200 hover:text-amber-900 ml-1">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-bold text-xs text-emerald-600 dark:text-emerald-400">
                            {{ __('Link verifikasi baru telah dikirimkan ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Nomor WhatsApp / HP -->
        <div>
            <label for="phone_number" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Nomor WhatsApp / HP') }}
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-emerald-500">
                    <i class="fab fa-whatsapp text-base"></i>
                </div>
                <input id="phone_number" 
                       name="phone_number" 
                       type="text" 
                       class="block w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm" 
                       value="{{ old('phone_number', $user->phone_number) }}" 
                       placeholder="Contoh: 081234567890" 
                       autocomplete="tel" />
            </div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                Nomor ini digunakan untuk notifikasi transaksi, konfirmasi pembayaran invoice, dan pesan layanan otomatis.
            </p>
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('phone_number')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-lg shadow-blue-500/20 flex items-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20"
                >
                    <i class="fas fa-check-circle"></i> {{ __('Profil & Nomor HP berhasil disimpan!') }}
                </div>
            @endif
        </div>
    </form>
</section>
