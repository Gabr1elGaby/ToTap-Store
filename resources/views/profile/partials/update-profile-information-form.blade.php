<section>
    <header class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-gray-100 dark:border-gray-700/60 pb-4 mb-6">
        <div>
            <h2 class="text-xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-2.5">
                <i class="fas fa-user-circle text-blue-500 text-2xl"></i>
                {{ __('Informasi Akun & Kontak') }}
            </h2>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 font-medium">
                {{ __('Perbarui data profil, alamat email, dan verifikasi nomor WhatsApp akun Anda.') }}
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

    <!-- FORM 1: NAMA & EMAIL -->
    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Nama Lengkap') }} <span class="text-red-500">*</span>
            </label>
            <input id="name" 
                   name="name" 
                   type="text" 
                   class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                   value="{{ old('name', $user->name) }}" 
                   required 
                   autofocus 
                   autocomplete="name" 
                   placeholder="Contoh: Super Admin" />
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('name')" />
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300 mb-1.5">
                {{ __('Alamat Email') }} <span class="text-red-500">*</span>
            </label>
            <input id="email" 
                   name="email" 
                   type="email" 
                   class="block w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm" 
                   value="{{ old('email', $user->email) }}" 
                   required 
                   autocomplete="username" 
                   placeholder="nama@email.com" />
            <x-input-error class="mt-1.5 text-xs" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold uppercase tracking-wider transition shadow-lg shadow-blue-500/20 flex items-center gap-2 cursor-pointer">
                <i class="fas fa-save"></i> {{ __('Simpan Nama & Email') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-3 py-1.5 rounded-lg border border-emerald-500/20"
                >
                    <i class="fas fa-check-circle"></i> {{ __('Profil berhasil diperbarui!') }}
                </div>
            @endif
        </div>
    </form>

    <!-- FORM 2: UBAH NOMOR WHATSAPP DENGAN OTP VERIFIKASI (FONNTE) -->
    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60" x-data="{
        currentPhone: '{{ $user->phone_number ?? '' }}',
        newPhone: '',
        otpCode: '',
        step: 1, // 1 = Input Phone, 2 = Input OTP
        loading: false,
        errMsg: '',
        successMsg: '',
        countdown: 0,
        timerInterval: null,

        startTimer() {
            this.countdown = 60;
            if (this.timerInterval) clearInterval(this.timerInterval);
            this.timerInterval = setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    clearInterval(this.timerInterval);
                }
            }, 1000);
        },

        async sendOtp() {
            this.errMsg = '';
            this.successMsg = '';
            if (!this.newPhone || this.newPhone.trim().length < 9) {
                this.errMsg = 'Masukkan nomor WhatsApp baru yang valid (minimal 9 digit).';
                return;
            }
            if (this.newPhone === this.currentPhone) {
                this.errMsg = 'Nomor WhatsApp baru sama dengan nomor yang saat ini aktif.';
                return;
            }

            this.loading = true;
            try {
                const res = await fetch('{{ route('profile.phone.send-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ phone_number: this.newPhone })
                });
                const data = await res.json();
                this.loading = false;

                if (!res.ok || !data.success) {
                    this.errMsg = data.message || (data.errors && data.errors.phone_number ? data.errors.phone_number[0] : 'Gagal mengirim kode OTP.');
                    return;
                }

                this.step = 2;
                this.successMsg = data.message;
                this.startTimer();
            } catch (err) {
                this.loading = false;
                this.errMsg = 'Terjadi kesalahan jaringan. Coba lagi.';
            }
        },

        async verifyOtp() {
            this.errMsg = '';
            this.successMsg = '';
            if (!this.otpCode || this.otpCode.trim().length !== 6) {
                this.errMsg = 'Masukkan 6 digit kode OTP yang diterima di WhatsApp.';
                return;
            }

            this.loading = true;
            try {
                const res = await fetch('{{ route('profile.phone.verify-otp') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ otp: this.otpCode })
                });
                const data = await res.json();
                this.loading = false;

                if (!res.ok || !data.success) {
                    this.errMsg = data.message || 'Kode OTP salah atau kedaluwarsa.';
                    return;
                }

                this.currentPhone = data.phone_number;
                this.step = 1;
                this.newPhone = '';
                this.otpCode = '';
                this.successMsg = '✅ ' + data.message;
            } catch (err) {
                this.loading = false;
                this.errMsg = 'Gagal memverifikasi OTP. Coba lagi.';
            }
        }
    }">
        <div class="flex items-center justify-between mb-3">
            <div>
                <h3 class="text-sm font-bold uppercase tracking-wider text-gray-900 dark:text-white flex items-center gap-2">
                    <i class="fab fa-whatsapp text-emerald-500 text-base"></i>
                    {{ __('Nomor WhatsApp / HP Terdaftar') }}
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Nomor WhatsApp digunakan untuk konfirmasi invoice, keamanan akun, dan verifikasi OTP.
                </p>
            </div>
            <div>
                <template x-if="currentPhone">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 rounded-full text-xs font-bold">
                        <i class="fas fa-check-circle"></i> <span x-text="currentPhone"></span>
                    </span>
                </template>
                <template x-if="!currentPhone">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/30 text-amber-600 dark:text-amber-400 rounded-full text-xs font-bold">
                        <i class="fas fa-exclamation-circle"></i> Belum Diatur
                    </span>
                </template>
            </div>
        </div>

        <!-- Alert Error / Success -->
        <template x-if="errMsg">
            <div class="mb-4 p-3.5 rounded-xl bg-red-500/10 border border-red-500/30 text-red-600 dark:text-red-400 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-times-circle text-sm"></i>
                <span x-text="errMsg"></span>
            </div>
        </template>
        <template x-if="successMsg">
            <div class="mb-4 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-check-circle text-sm"></i>
                <span x-text="successMsg"></span>
            </div>
        </template>

        <!-- STEP 1: Input Nomor WhatsApp Baru -->
        <div x-show="step === 1" class="space-y-3 bg-gray-50 dark:bg-gray-900/60 p-4 rounded-2xl border border-gray-200 dark:border-gray-700/80">
            <label class="block text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">
                {{ __('Ubah / Pasang Nomor WhatsApp Baru') }}
            </label>
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1">
                    <input type="text" 
                           x-model="newPhone" 
                           placeholder="Contoh: 081234567890" 
                           class="block w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm font-semibold text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm" />
                </div>
                <button type="button" 
                        @click="sendOtp()" 
                        :disabled="loading"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-xs font-bold uppercase tracking-wider transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer whitespace-nowrap">
                    <template x-if="loading">
                        <i class="fas fa-spinner fa-spin"></i>
                    </template>
                    <template x-if="!loading">
                        <i class="fas fa-paper-plane"></i>
                    </template>
                    <span>Kirim OTP WhatsApp</span>
                </button>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-gray-400">
                Sistem akan mengirim 6 digit kode OTP verifikasi ke nomor WhatsApp di atas sebelum nomor diganti.
            </p>
        </div>

        <!-- STEP 2: Input Kode OTP 6 Digit -->
        <div x-show="step === 2" class="space-y-4 bg-emerald-500/5 dark:bg-emerald-950/20 p-5 rounded-2xl border border-emerald-500/30">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-300 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i> Masukkan 6 Digit Kode OTP
                    </h4>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">
                        Kode telah dikirimkan ke WhatsApp: <strong class="text-emerald-600 dark:text-emerald-400" x-text="newPhone"></strong>
                    </p>
                </div>
                <button type="button" @click="step = 1; errMsg = '';" class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 underline font-semibold cursor-pointer">
                    Ganti Nomor
                </button>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" 
                       x-model="otpCode" 
                       maxlength="6"
                       placeholder="• • • • • •" 
                       class="block w-full sm:w-48 text-center tracking-[0.4em] py-2.5 bg-white dark:bg-gray-900 border-2 border-emerald-500/50 rounded-xl text-lg font-black text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition shadow-sm" />
                
                <button type="button" 
                        @click="verifyOtp()" 
                        :disabled="loading || otpCode.length !== 6"
                        class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white text-xs font-bold uppercase tracking-wider transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 cursor-pointer">
                    <template x-if="loading">
                        <i class="fas fa-spinner fa-spin"></i>
                    </template>
                    <template x-if="!loading">
                        <i class="fas fa-check-circle"></i>
                    </template>
                    <span>Verifikasi & Simpan</span>
                </button>

                <button type="button" 
                        @click="sendOtp()" 
                        :disabled="loading || countdown > 0"
                        class="px-4 py-2.5 rounded-xl bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 disabled:opacity-50 text-gray-700 dark:text-gray-300 text-xs font-bold uppercase tracking-wider transition flex items-center justify-center gap-1.5 cursor-pointer whitespace-nowrap">
                    <i class="fas fa-redo-alt"></i>
                    <span x-text="countdown > 0 ? 'Kirim Ulang (' + countdown + 's)' : 'Kirim Ulang OTP'"></span>
                </button>
            </div>
        </div>
    </div>
</section>
