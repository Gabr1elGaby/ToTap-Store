<!-- Auth Modals (High Reliability System) -->
<div id="modal-login-backdrop" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/80 backdrop-blur-sm p-4 transition-all duration-200">
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-3xl shadow-2xl p-6 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Masuk Akun</h2>
            <button type="button" onclick="closeLoginModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form id="ajax-login-form" class="space-y-4" onsubmit="handleAjaxLogin(event)">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="email" name="email" required autofocus>
                <span id="login-error-email" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password" required>
                <div class="flex justify-end mt-1.5">
                    <button type="button" onclick="openForgotPasswordModal()" class="text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">Lupa Password?</button>
                </div>
                <span id="login-error-password" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <button id="login-submit-btn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition mt-2 shadow-md shadow-blue-500/20 text-sm">
                Masuk
            </button>
        </form>

        <!-- Tombol Login dengan Google -->
        <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60">
            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs shadow-sm transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Masuk dengan Google</span>
            </a>
        </div>
        
        <div class="mt-4 text-center text-xs text-gray-600 dark:text-gray-400">
            Belum punya akun? <button type="button" onclick="openRegisterModal()" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">Daftar Sekarang</button>
        </div>
    </div>
</div>

<div id="modal-register-backdrop" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/80 backdrop-blur-sm p-4 transition-all duration-200">
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-3xl shadow-2xl p-6 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex justify-between items-center mb-6">
            <h2 id="register-modal-title" class="text-xl font-bold text-gray-900 dark:text-white">Registrasi Klien Baru</h2>
            <button type="button" onclick="closeRegisterModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Step 1: Form Registrasi -->
        <form id="ajax-register-form" class="space-y-3.5" onsubmit="handleAjaxRegister(event)">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap / Organisasi</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="text" name="name" required autofocus>
                <span id="reg-error-name" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="email" name="email" required>
                <span id="reg-error-email" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Nomor WhatsApp Aktif</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="text" name="phone_number" placeholder="08xxxxxxxxxx" required>
                <span id="reg-error-phone_number" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
                <span id="reg-error-phone" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password" required>
                <span id="reg-error-password" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                <input class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="password" name="password_confirmation" required>
            </div>
            <button id="register-submit-btn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition mt-2 shadow-md shadow-blue-500/20 text-sm">
                Daftar Akun
            </button>
        </form>

        <!-- Tombol Register dengan Google -->
        <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700/60">
            <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-2.5 py-2.5 px-4 rounded-xl border border-gray-300 dark:border-gray-700 bg-slate-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-200 font-bold text-xs shadow-sm transition">
                <svg class="w-4 h-4" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
                </svg>
                <span>Daftar Cepat dengan Google</span>
            </a>
        </div>

        <!-- Step 2: Form OTP WhatsApp -->
        <form id="ajax-otp-form" class="space-y-4 hidden" onsubmit="handleAjaxOtp(event)">
            @csrf
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-950 text-green-600 dark:text-green-400 rounded-2xl flex items-center justify-center mx-auto mb-3 text-2xl">
                    <i class="fab fa-whatsapp"></i>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400">Kode OTP telah dikirimkan ke WhatsApp Anda: <br><strong id="otp-phone-display" class="text-gray-900 dark:text-white"></strong></p>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1 text-center">Masukkan 6 Digit Kode OTP</label>
                <input id="otp-input-field" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl text-center text-xl font-bold tracking-widest focus:border-green-500 focus:ring-1 focus:ring-green-500" type="text" name="otp" maxlength="6" required>
                <span id="otp-error-msg" class="text-red-500 text-xs mt-1 block font-medium text-center hidden"></span>
            </div>
            <button id="otp-submit-btn" type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl transition shadow-md shadow-green-500/20 text-sm">
                Verifikasi OTP
            </button>
            <div class="text-center mt-2">
                <button type="button" onclick="backToRegisterForm()" class="text-xs text-gray-500 hover:underline">Ganti Nomor HP</button>
            </div>
        </form>
        
        <div id="register-footer" class="mt-5 text-center text-xs text-gray-600 dark:text-gray-400">
            Sudah punya akun? <button type="button" onclick="openLoginModal()" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">Masuk</button>
        </div>
    </div>
</div>

<!-- Modal Forgot Password -->
<div id="modal-forgot-backdrop" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-950/80 backdrop-blur-sm p-4 transition-all duration-200">
    <div class="w-full max-w-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-200 dark:border-gray-700 rounded-3xl shadow-2xl p-6 relative animate-in fade-in zoom-in-95 duration-150">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Lupa Password</h2>
            <button type="button" onclick="closeForgotPasswordModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <p class="text-xs text-gray-600 dark:text-gray-400 mb-4">
            Masukkan email akun Anda. Kami akan mengirimkan tautan untuk mengatur ulang password baru.
        </p>

        <div id="forgot-success-alert" class="hidden mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded-xl text-xs text-green-600 dark:text-green-400 font-medium">
        </div>

        <form id="ajax-forgot-form" class="space-y-4" onsubmit="handleAjaxForgot(event)">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-1">Email Terdaftar</label>
                <input id="forgot-email-input" class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-gray-900 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-700 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-sm" type="email" name="email" placeholder="nama@email.com" required autofocus>
                <span id="forgot-error-email" class="text-red-500 text-xs mt-1 block font-medium hidden"></span>
            </div>
            <button id="forgot-submit-btn" type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition shadow-md shadow-blue-500/20 text-sm">
                Kirim Link Reset
            </button>
        </form>
        
        <div class="mt-5 text-center text-xs text-gray-600 dark:text-gray-400">
            Ingat password Anda? <button type="button" onclick="openLoginModal()" class="text-blue-600 dark:text-blue-400 hover:underline font-bold">Kembali ke Login</button>
        </div>
    </div>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6285198503253?text=Halo%20Admin%20ToTap%20Store,%20saya%20ingin%20bertanya%20tentang%20layanan%20Anda." target="_blank" class="fixed bottom-6 right-6 bg-[#25D366] text-white p-3.5 rounded-full shadow-xl hover:scale-110 hover:shadow-2xl transition-transform duration-300 z-50 flex items-center justify-center group border-2 border-white" title="Hubungi Customer Service">
    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    </svg>
    <span class="absolute right-full mr-4 bg-gray-900 text-white text-sm font-bold px-3 py-1.5 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none border border-gray-700">
        Hubungi Kami
    </span>
</a>

<script>
    function openLoginModal() {
        document.getElementById('modal-login-backdrop').classList.remove('hidden');
        document.getElementById('modal-register-backdrop').classList.add('hidden');
        document.getElementById('modal-forgot-backdrop').classList.add('hidden');
    }
    function closeLoginModal() {
        document.getElementById('modal-login-backdrop').classList.add('hidden');
    }
    function openRegisterModal() {
        document.getElementById('modal-register-backdrop').classList.remove('hidden');
        document.getElementById('modal-login-backdrop').classList.add('hidden');
        document.getElementById('modal-forgot-backdrop').classList.add('hidden');
        backToRegisterForm();
    }
    function closeRegisterModal() {
        document.getElementById('modal-register-backdrop').classList.add('hidden');
    }
    function openForgotPasswordModal() {
        document.getElementById('modal-forgot-backdrop').classList.remove('hidden');
        document.getElementById('modal-login-backdrop').classList.add('hidden');
        document.getElementById('modal-register-backdrop').classList.add('hidden');
        document.getElementById('forgot-error-email').classList.add('hidden');
        document.getElementById('forgot-success-alert').classList.add('hidden');
    }
    function closeForgotPasswordModal() {
        document.getElementById('modal-forgot-backdrop').classList.add('hidden');
    }
    function backToRegisterForm() {
        document.getElementById('ajax-register-form').classList.remove('hidden');
        document.getElementById('ajax-otp-form').classList.add('hidden');
        document.getElementById('register-modal-title').innerText = 'Registrasi Klien Baru';
        document.getElementById('register-footer').classList.remove('hidden');
    }

    let globalRegisteredPhone = '';

    function handleAjaxLogin(e) {
        e.preventDefault();
        const btn = document.getElementById('login-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Memproses...';
        
        document.getElementById('login-error-email').classList.add('hidden');
        document.getElementById('login-error-password').classList.add('hidden');

        fetch('{{ route('login') }}', {
            method: 'POST',
            body: new FormData(e.target),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(async res => {
            if (res.ok) {
                window.location.reload();
            } else {
                const data = await res.json();
                const errSpan = document.getElementById('login-error-email');
                errSpan.innerText = (data.errors && data.errors.email ? data.errors.email[0] : (data.message || 'Email atau password salah.'));
                errSpan.classList.remove('hidden');
            }
            btn.disabled = false;
            btn.innerText = 'Masuk';
        }).catch(err => {
            const errSpan = document.getElementById('login-error-email');
            errSpan.innerText = 'Terjadi kesalahan koneksi.';
            errSpan.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Masuk';
        });
    }

    function handleAjaxForgot(e) {
        e.preventDefault();
        const btn = document.getElementById('forgot-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Mengirim...';

        const errSpan = document.getElementById('forgot-error-email');
        const successAlert = document.getElementById('forgot-success-alert');
        errSpan.classList.add('hidden');
        successAlert.classList.add('hidden');

        fetch('{{ route('password.email') }}', {
            method: 'POST',
            body: new FormData(e.target),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(async res => {
            const data = await res.json();
            if (res.ok) {
                successAlert.innerText = data.message || 'Link reset password telah dikirim ke email Anda!';
                successAlert.classList.remove('hidden');
                document.getElementById('forgot-email-input').value = '';
            } else {
                errSpan.innerText = (data.errors && data.errors.email ? data.errors.email[0] : (data.message || 'Gagal mengirim link reset password.'));
                errSpan.classList.remove('hidden');
            }
            btn.disabled = false;
            btn.innerText = 'Kirim Link Reset';
        }).catch(err => {
            errSpan.innerText = 'Terjadi kesalahan koneksi server.';
            errSpan.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Kirim Link Reset';
        });
    }

    function handleAjaxRegister(e) {
        e.preventDefault();
        const btn = document.getElementById('register-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Memproses...';

        document.querySelectorAll('[id^="reg-error-"]').forEach(el => el.classList.add('hidden'));

        fetch('{{ route('register') }}', {
            method: 'POST',
            body: new FormData(e.target),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(async res => {
            if (res.ok) {
                const data = await res.json();
                if (data.requires_otp) {
                    globalRegisteredPhone = data.phone;
                    document.getElementById('otp-phone-display').innerText = data.phone;
                    document.getElementById('ajax-register-form').classList.add('hidden');
                    document.getElementById('ajax-otp-form').classList.remove('hidden');
                    document.getElementById('register-modal-title').innerText = 'Verifikasi WhatsApp';
                    document.getElementById('register-footer').classList.add('hidden');
                } else {
                    window.location.reload();
                }
            } else {
                const data = await res.json();
                if (data.errors) {
                    for (let [field, msgs] of Object.entries(data.errors)) {
                        let el = document.getElementById('reg-error-' + field);
                        if (!el && field === 'phone_number') el = document.getElementById('reg-error-phone');
                        if (!el && field === 'phone') el = document.getElementById('reg-error-phone_number');
                        if (el) {
                            el.innerText = msgs[0];
                            el.classList.remove('hidden');
                        }
                    }
                } else if (data.message) {
                    const el = document.getElementById('reg-error-email');
                    if (el) {
                        el.innerText = data.message;
                        el.classList.remove('hidden');
                    }
                }
            }
            btn.disabled = false;
            btn.innerText = 'Daftar Akun';
        }).catch(err => {
            btn.disabled = false;
            btn.innerText = 'Daftar Akun';
        });
    }

    function handleAjaxOtp(e) {
        e.preventDefault();
        const btn = document.getElementById('otp-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Memproses...';

        const otp = document.getElementById('otp-input-field').value;
        const errSpan = document.getElementById('otp-error-msg');
        errSpan.classList.add('hidden');

        fetch('{{ route('register.verify-otp') }}', {
            method: 'POST',
            body: JSON.stringify({ phone: globalRegisteredPhone, otp: otp }),
            headers: { 
                'Content-Type': 'application/json',
                'Accept': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest' 
            }
        }).then(async res => {
            if (res.ok) {
                window.location.reload();
            } else {
                const data = await res.json();
                errSpan.innerText = data.message || 'Kode OTP salah atau kedaluwarsa.';
                errSpan.classList.remove('hidden');
            }
            btn.disabled = false;
            btn.innerText = 'Verifikasi OTP';
        }).catch(err => {
            errSpan.innerText = 'Gagal verifikasi OTP.';
            errSpan.classList.remove('hidden');
            btn.disabled = false;
            btn.innerText = 'Verifikasi OTP';
        });
    }

    window.addEventListener('open-login', function() {
        openLoginModal();
    });
    window.addEventListener('open-register', function() {
        openRegisterModal();
    });
    window.addEventListener('open-forgot-password', function() {
        openForgotPasswordModal();
    });

    // Automatically check for ?login=1 or ?forgot=1 in URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('login') === '1') {
            openLoginModal();
        } else if (urlParams.get('forgot') === '1') {
            openForgotPasswordModal();
        }
    });
</script>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: false,
        offset: 50,
        duration: 800,
    });
</script>