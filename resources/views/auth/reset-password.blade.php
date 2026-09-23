<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Atur Ulang Password</h2>
        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
            Verifikasi nomor WhatsApp terdaftar Anda untuk mengatur ulang password baru.
        </p>
    </div>

    <!-- Alert Status -->
    <div id="alert-box" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium border text-center transition-all"></div>

    <!-- Error Summary if any (Laravel fallback) -->
    @if ($errors->any())
        <div class="mb-4 p-3 bg-rose-500/10 border border-rose-500/30 rounded-xl text-xs text-rose-500 font-medium">
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- STEP 1: INPUT NO WA & KIRIM OTP -->
    <div id="step-phone" class="space-y-4">
        <!-- Password Reset Token -->
        <input type="hidden" id="reset_token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email Terdaftar</label>
            <input id="email" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm opacity-80" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly autocomplete="username" />
        </div>

        <!-- Nomor WhatsApp Terdaftar -->
        <div>
            <label for="phone_number" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp Terdaftar</label>
            <input id="phone_number" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="tel" name="phone_number" value="{{ old('phone_number') }}" required autofocus placeholder="Contoh: 081234567890" />
            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">Kode OTP 6-digit akan dikirimkan ke WhatsApp nomor di atas.</p>
        </div>

        <button type="button" id="btn-send-otp" onclick="sendOtp()"
                class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-emerald-600/20 text-sm flex items-center justify-center gap-2 cursor-pointer">
            <i class="fab fa-whatsapp text-base"></i>
            <span id="btn-send-text">Kirim Kode OTP (WhatsApp)</span>
        </button>

        <div class="text-center pt-2">
            <a href="/" class="text-xs text-gray-500 hover:text-blue-500 transition">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </div>

    <!-- STEP 2: INPUT OTP & PASSWORD BARU -->
    <div id="step-password" class="space-y-4 hidden">
        <div>
            <div class="flex justify-between items-center mb-1">
                <label for="otp" class="block text-xs font-bold text-gray-700 dark:text-gray-300">Kode OTP WhatsApp (6 Digit)</label>
                <button type="button" onclick="resetToPhoneStep()" class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">Ubah No. HP</button>
            </div>
            <input id="otp" class="w-full text-center tracking-[0.4em] text-xl font-bold py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500" type="text" maxlength="6" placeholder="123456" autocomplete="one-time-code" />
        </div>

        <!-- Password Baru -->
        <div>
            <label for="password" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
            <input id="password" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
        </div>

        <!-- Konfirmasi Password Baru -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password Baru</label>
            <input id="password_confirmation" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password baru" />
        </div>

        <button type="button" id="btn-submit-reset" onclick="submitPasswordReset()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition shadow-md shadow-blue-500/20 text-sm flex items-center justify-center gap-2 cursor-pointer">
            <i class="fas fa-check-circle text-base"></i>
            <span id="btn-submit-text">Verifikasi OTP & Simpan Password</span>
        </button>

        <div class="text-center pt-2">
            <button type="button" onclick="sendOtp()" class="text-xs text-gray-500 dark:text-gray-400 hover:text-emerald-600 transition">
                Tidak menerima kode? <span class="font-bold underline text-emerald-600 dark:text-emerald-400">Kirim Ulang OTP</span>
            </button>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showAlert(message, type = 'error') {
            const alertBox = document.getElementById('alert-box');
            alertBox.classList.remove('hidden', 'bg-rose-500/10', 'border-rose-500/30', 'text-rose-600', 'dark:text-rose-400', 'bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-600', 'dark:text-emerald-400');

            if (type === 'error') {
                alertBox.classList.add('bg-rose-500/10', 'border-rose-500/30', 'text-rose-600', 'dark:text-rose-400');
            } else {
                alertBox.classList.add('bg-emerald-500/10', 'border-emerald-500/30', 'text-emerald-600', 'dark:text-emerald-400');
            }

            alertBox.innerText = message;
        }

        function hideAlert() {
            document.getElementById('alert-box').classList.add('hidden');
        }

        function sendOtp() {
            const emailInput = document.getElementById('email').value.trim();
            const phoneInput = document.getElementById('phone_number').value.trim();

            if (!phoneInput) {
                showAlert('Silakan masukkan nomor WhatsApp terdaftar Anda.');
                return;
            }

            hideAlert();
            const btnSend = document.getElementById('btn-send-otp');
            const btnText = document.getElementById('btn-send-text');
            btnSend.disabled = true;
            btnText.innerText = 'Memeriksa & Mengirim OTP...';

            fetch("{{ route('password.send_otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ email: emailInput, phone_number: phoneInput })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                btnSend.disabled = false;
                btnText.innerText = 'Kirim Kode OTP (WhatsApp)';

                if (res.status === 200) {
                    document.getElementById('step-phone').classList.add('hidden');
                    document.getElementById('step-password').classList.remove('hidden');
                    showAlert(res.body.message, 'success');
                    document.getElementById('otp').focus();
                } else {
                    showAlert(res.body.message || 'Pengecekan gagal. Pastikan nomor WhatsApp sesuai.');
                }
            })
            .catch(err => {
                btnSend.disabled = false;
                btnText.innerText = 'Kirim Kode OTP (WhatsApp)';
                showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            });
        }

        function submitPasswordReset() {
            const token = document.getElementById('reset_token').value;
            const emailInput = document.getElementById('email').value.trim();
            const phoneInput = document.getElementById('phone_number').value.trim();
            const otpInput = document.getElementById('otp').value.trim();
            const passwordInput = document.getElementById('password').value;
            const passwordConfirmationInput = document.getElementById('password_confirmation').value;

            if (!passwordInput || passwordInput.length < 8) {
                showAlert('Password minimal 8 karakter.');
                return;
            }

            if (passwordInput !== passwordConfirmationInput) {
                showAlert('Konfirmasi password tidak cocok.');
                return;
            }

            hideAlert();
            const btnSubmit = document.getElementById('btn-submit-reset');
            const btnText = document.getElementById('btn-submit-text');
            btnSubmit.disabled = true;
            btnText.innerText = 'Memverifikasi & Menyimpan...';

            fetch("{{ route('password.store') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    token: token,
                    email: emailInput,
                    phone_number: phoneInput,
                    otp: otpInput,
                    password: passwordInput,
                    password_confirmation: passwordConfirmationInput
                })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                btnSubmit.disabled = false;
                btnText.innerText = 'Verifikasi OTP & Simpan Password';

                if (res.status === 200 && res.body.success) {
                    showAlert(res.body.message, 'success');
                    setTimeout(() => { window.location.href = "/?login=1"; }, 1200);
                } else {
                    showAlert(res.body.message || (res.body.errors ? Object.values(res.body.errors).flat().join(' ') : 'Gagal memperbarui password.'));
                }
            })
            .catch(err => {
                btnSubmit.disabled = false;
                btnText.innerText = 'Verifikasi OTP & Simpan Password';
                showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            });
        }

        function resetToPhoneStep() {
            hideAlert();
            document.getElementById('step-password').classList.add('hidden');
            document.getElementById('step-phone').classList.remove('hidden');
            document.getElementById('phone_number').focus();
        }
    </script>
</x-guest-layout>
