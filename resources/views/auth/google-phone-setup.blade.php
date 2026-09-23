<x-guest-layout>
    <div class="py-2">
        <!-- Header -->
        <div class="text-center mb-6">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 flex items-center justify-center mb-3 shadow-inner">
                <svg class="w-8 h-8 fill-current text-emerald-500" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.572-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white tracking-tight">Verifikasi WhatsApp</h2>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5 max-w-xs mx-auto leading-relaxed">
                Halo <span class="font-bold text-gray-800 dark:text-gray-200">{{ $user->name }}</span>,<br>masukkan nomor WhatsApp Anda untuk menyelesaikan pendaftaran.
            </p>
        </div>

        <!-- Alert Status -->
        <div id="alert-box" class="hidden mb-4 p-3.5 rounded-xl text-xs font-medium border text-center transition-all"></div>

        <!-- STEP 1: INPUT NOMOR HP -->
        <div id="step-phone" class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1.5">Nomor WhatsApp</label>
                <div class="flex items-center rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500 transition overflow-hidden">
                    <span class="px-3.5 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 font-bold text-sm border-r border-gray-300 dark:border-gray-700 flex items-center select-none shrink-0">
                        +62
                    </span>
                    <input type="tel" id="phone_number" placeholder="81234567890" autofocus autocomplete="tel"
                           class="w-full bg-transparent px-3.5 py-3 text-gray-900 dark:text-white text-sm font-semibold border-none outline-none focus:outline-none focus:ring-0">
                </div>
                <p class="text-[11px] text-gray-400 mt-1.5">Contoh: 81234567890 atau 081234567890</p>
            </div>

            <button type="button" id="btn-send-otp" onclick="sendOtp()"
                    class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-[0.99] text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.572-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span id="btn-send-text">Kirim Kode OTP</span>
            </button>
        </div>

        <!-- STEP 2: INPUT KODE OTP -->
        <div id="step-otp" class="space-y-4 hidden">
            <div>
                <div class="flex justify-between items-center mb-1.5">
                    <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Kode OTP 6-Digit</label>
                    <button type="button" onclick="resetToPhoneStep()" class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline">Ubah No. HP</button>
                </div>
                <input type="text" id="otp_code" maxlength="6" placeholder="123456" autocomplete="one-time-code"
                       class="w-full text-center tracking-[0.5em] text-2xl font-bold py-3.5 rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                <p class="text-[11px] text-gray-400 mt-1.5 text-center">Kode telah dikirim ke WhatsApp <span id="target-phone-display" class="font-bold text-gray-700 dark:text-gray-300"></span></p>
            </div>

            <button type="button" id="btn-verify-otp" onclick="verifyOtp()"
                    class="w-full py-3.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-500 active:scale-[0.99] text-white font-bold text-sm shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2 cursor-pointer">
                <svg class="w-5 h-5 fill-none stroke-current stroke-2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span id="btn-verify-text">Verifikasi & Masuk</span>
            </button>

            <div class="text-center pt-2">
                <button type="button" id="btn-resend-otp" onclick="sendOtp()" class="text-xs text-gray-500 dark:text-gray-400 hover:text-emerald-500 transition">
                    Tidak menerima kode? <span class="font-bold underline text-emerald-600 dark:text-emerald-400">Kirim Ulang OTP</span>
                </button>
            </div>
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
