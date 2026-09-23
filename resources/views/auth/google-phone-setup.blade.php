<x-guest-layout>
    <div class="text-center mb-6">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 mb-3">
            <i class="fab fa-whatsapp text-2xl"></i>
        </div>
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Verifikasi WhatsApp</h2>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
            Halo <span class="font-semibold text-gray-700 dark:text-gray-200">{{ $user->name }}</span> ({{ $user->email }}), masukkan nomor WhatsApp aktif Anda untuk menyelesaikan pendaftaran.
        </p>
    </div>

    <!-- Alert Status -->
    <div id="alert-box" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium border"></div>

    <!-- STEP 1: INPUT NOMOR HP -->
    <div id="step-phone" class="space-y-4">
        <div>
            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp</label>
            <div class="relative flex items-center">
                <span class="absolute left-3 text-sm font-bold text-gray-500 dark:text-gray-400 select-none">+62</span>
                <input type="tel" id="phone_number" placeholder="81234567890" autofocus
                       class="w-full pl-12 pr-4 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            </div>
            <p class="text-[11px] text-gray-400 mt-1">Contoh: 81234567890 atau 081234567890</p>
        </div>

        <button type="button" id="btn-send-otp" onclick="sendOtp()"
                class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/20 transition flex items-center justify-center gap-2">
            <i class="fab fa-whatsapp text-base"></i>
            <span id="btn-send-text">Kirim Kode OTP</span>
        </button>
    </div>

    <!-- STEP 2: INPUT KODE OTP -->
    <div id="step-otp" class="space-y-4 hidden">
        <div>
            <div class="flex justify-between items-center mb-1">
                <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300">Kode OTP 6-Digit</label>
                <button type="button" onclick="resetToPhoneStep()" class="text-[11px] text-emerald-500 hover:underline">Ubah No. HP</button>
            </div>
            <input type="text" id="otp_code" maxlength="6" placeholder="123456" autocomplete="one-time-code"
                   class="w-full text-center tracking-[0.5em] text-xl font-bold py-3 rounded-xl border border-gray-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
            <p class="text-[11px] text-gray-400 mt-1 text-center">Kode dikirimkan via pesan WhatsApp ke <span id="target-phone-display" class="font-bold text-gray-700 dark:text-gray-300"></span></p>
        </div>

        <button type="button" id="btn-verify-otp" onclick="verifyOtp()"
                class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-lg shadow-emerald-600/20 transition flex items-center justify-center gap-2">
            <i class="fas fa-check-circle text-base"></i>
            <span id="btn-verify-text">Verifikasi & Masuk</span>
        </button>

        <div class="text-center pt-2">
            <button type="button" id="btn-resend-otp" onclick="sendOtp()" class="text-xs text-gray-500 dark:text-gray-400 hover:text-emerald-500 transition">
                Tidak menerima kode? <span class="font-bold underline">Kirim Ulang OTP</span>
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
            const phoneInput = document.getElementById('phone_number').value.trim();
            if (!phoneInput) {
                showAlert('Silakan masukkan nomor WhatsApp Anda.');
                return;
            }

            hideAlert();
            const btnSend = document.getElementById('btn-send-otp');
            const btnText = document.getElementById('btn-send-text');
            btnSend.disabled = true;
            btnText.innerText = 'Mengirim OTP...';

            fetch("{{ route('auth.google.send_otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ phone_number: phoneInput })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                btnSend.disabled = false;
                btnText.innerText = 'Kirim Kode OTP';

                if (res.status === 200) {
                    if (res.body.requires_otp === false) {
                        showAlert('Nomor terverifikasi. Mengalihkan...', 'success');
                        setTimeout(() => { window.location.href = "/"; }, 1000);
                    } else {
                        document.getElementById('step-phone').classList.add('hidden');
                        document.getElementById('step-otp').classList.remove('hidden');
                        document.getElementById('target-phone-display').innerText = '+' + res.body.phone;
                        showAlert(res.body.message, 'success');
                        document.getElementById('otp_code').focus();
                    }
                } else {
                    showAlert(res.body.message || 'Gagal mengirim OTP. Coba lagi.');
                }
            })
            .catch(err => {
                btnSend.disabled = false;
                btnText.innerText = 'Kirim Kode OTP';
                showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            });
        }

        function verifyOtp() {
            const otpInput = document.getElementById('otp_code').value.trim();
            if (!otpInput || otpInput.length !== 6) {
                showAlert('Masukkan 6 digit kode OTP yang dikirim ke WhatsApp.');
                return;
            }

            hideAlert();
            const btnVerify = document.getElementById('btn-verify-otp');
            const btnText = document.getElementById('btn-verify-text');
            btnVerify.disabled = true;
            btnText.innerText = 'Memverifikasi...';

            fetch("{{ route('auth.google.verify_otp') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    "Accept": "application/json"
                },
                body: JSON.stringify({ otp: otpInput })
            })
            .then(res => res.json().then(data => ({ status: res.status, body: data })))
            .then(res => {
                btnVerify.disabled = false;
                btnText.innerText = 'Verifikasi & Masuk';

                if (res.status === 200 && res.body.success) {
                    showAlert(res.body.message, 'success');
                    setTimeout(() => { window.location.href = "/"; }, 1000);
                } else {
                    showAlert(res.body.message || 'Verifikasi gagal. Coba lagi.');
                }
            })
            .catch(err => {
                btnVerify.disabled = false;
                btnText.innerText = 'Verifikasi & Masuk';
                showAlert('Terjadi kesalahan koneksi. Silakan coba lagi.');
            });
        }

        function resetToPhoneStep() {
            hideAlert();
            document.getElementById('step-otp').classList.add('hidden');
            document.getElementById('step-phone').classList.remove('hidden');
            document.getElementById('phone_number').focus();
        }
    </script>
</x-guest-layout>
