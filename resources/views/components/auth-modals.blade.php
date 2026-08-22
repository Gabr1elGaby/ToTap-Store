<!-- Auth Modals -->
<div x-show="showLogin" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 p-4" x-transition.opacity>
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6" x-data="{ errors: {}, loading: false }" @submit.prevent="
        loading = true; errors = {};
        fetch('{{ route('login') }}', {
            method: 'POST', 
            body: new FormData($event.target), 
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        }).then(async response => {
            if (response.ok) { 
                window.location.reload(); 
            } else if (response.status === 422) { 
                const data = await response.json();
                errors = data.errors || { email: [data.message || 'Email atau password salah.'] }; 
            } else if (response.status === 419) {
                errors = { email: ['Sesi kedaluwarsa. Silakan refresh halaman ini.'] };
            } else {
                errors = { email: ['Gagal masuk. Periksa kembali email & password Anda.'] };
            }
            loading = false;
        }).catch(err => {
            errors = { email: ['Terjadi kesalahan koneksi server.'] };
            loading = false;
        });
    ">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900">Masuk Akun</h2>
            <button @click="showLogin = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="email" name="email" required autofocus>
                <template x-if="errors.email"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.email[0]"></span></template>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="password" name="password" required>
                <template x-if="errors.password"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.password[0]"></span></template>
            </div>
            <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition mt-2 disabled:opacity-50 shadow-md">
                <span x-show="!loading">Masuk</span>
                <span x-show="loading">Memproses...</span>
            </button>
        </form>
        <div class="mt-4 text-center text-sm text-gray-600">
            Belum punya akun? <a href="#" @click.prevent="showLogin = false; showRegister = true" class="text-blue-600 hover:underline font-bold">Daftar</a>
        </div>
    </div>
</div>

<div x-show="showRegister" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 p-4" x-transition.opacity>
    <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6" x-data="{ errors: {}, loading: false, step: 'register', phone: '' }">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-900" x-text="step === 'register' ? 'Registrasi Klien Baru' : 'Verifikasi WhatsApp'"></h2>
            <button @click="showRegister = false" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <form x-show="step === 'register'" class="space-y-4" @submit.prevent="
            loading = true; errors = {};
            fetch('{{ route('register') }}', {
                method: 'POST', 
                body: new FormData($event.target), 
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(async response => {
                if (response.ok) { 
                    let data = await response.json();
                    if (data.requires_otp) {
                        step = 'otp';
                        phone = data.phone;
                    } else {
                        window.location.reload(); 
                    }
                } else if (response.status === 422) { 
                    errors = (await response.json()).errors; 
                }
                loading = false;
            }).catch(() => {
                loading = false;
            });
        ">
            @csrf
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nama Organisasi / Pemilik</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" name="name" required autofocus>
                <template x-if="errors.name"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.name[0]"></span></template>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Email Perusahaan</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="email" name="email" required>
                <template x-if="errors.email"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.email[0]"></span></template>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Nomor WhatsApp / HP</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="text" name="phone_number" required placeholder="08123456789">
                <template x-if="errors.phone_number"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.phone_number[0]"></span></template>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Password Akses</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="password" name="password" required>
                <template x-if="errors.password"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.password[0]"></span></template>
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Konfirmasi Password</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500" type="password" name="password_confirmation" required>
            </div>
            <button type="submit" :disabled="loading" class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-xl hover:bg-blue-700 transition mt-2 disabled:opacity-50 shadow-md">
                <span x-show="!loading">Daftar</span>
                <span x-show="loading">Memproses...</span>
            </button>
        </form>
        <div class="mt-4 text-center text-sm text-gray-600">
            Sudah punya lisensi? <a href="#" @click.prevent="showRegister = false; showLogin = true" class="text-blue-600 hover:underline font-bold">Masuk di sini</a>
        </div>

        <form x-show="step === 'otp'" style="display: none;" class="space-y-4" @submit.prevent="
            loading = true; errors = {};
            let formData = new FormData($event.target);
            formData.append('phone', phone);
            formData.append('_token', '{{ csrf_token() }}');
            fetch('{{ route('register.verify-otp') }}', {
                method: 'POST', 
                body: formData, 
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            }).then(async response => {
                if (response.ok) { window.location.reload(); }
                else if (response.status === 422) { errors = (await response.json()).errors; }
                loading = false;
            }).catch(() => {
                loading = false;
            });
        ">
            <div class="text-sm text-gray-600 mb-4">
                Kami telah mengirimkan 6 digit kode OTP ke nomor WhatsApp <strong x-text="phone"></strong>.
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-700 mb-1">Kode OTP</label>
                <input class="w-full px-3 py-2 bg-gray-50 text-gray-900 border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-1 focus:ring-blue-500 text-center tracking-widest text-lg font-bold" type="text" name="otp" required maxlength="6">
                <template x-if="errors.otp"><span class="text-red-600 text-xs mt-1 block font-medium" x-text="errors.otp[0]"></span></template>
            </div>
            <button type="submit" :disabled="loading" class="w-full bg-green-600 text-white font-bold py-2.5 rounded-xl hover:bg-green-700 transition mt-2 disabled:opacity-50 shadow-md">
                <span x-show="!loading">Verifikasi OTP</span>
                <span x-show="loading">Memproses...</span>
            </button>
            <div class="text-center mt-3">
                <button type="button" @click="step = 'register'" class="text-xs text-gray-500 hover:text-gray-800 underline">Ganti Nomor HP</button>
            </div>
        </form>
    </div>
</div>

<!-- Floating WhatsApp Button -->
<a href="https://wa.me/6281328972073?text=Halo%20Tim%20Gabriel%20Systems,%20saya%20ingin%20bertanya%20tentang%20sistem%20Anda." target="_blank" class="fixed bottom-6 right-6 bg-[#25D366] text-white p-3.5 rounded-full shadow-xl hover:scale-110 hover:shadow-2xl transition-transform duration-300 z-50 flex items-center justify-center group border-2 border-white" title="Hubungi Customer Service">
    <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.601 2.326A7.85 7.85 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.9 7.9 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.9 7.9 0 0 0 13.6 2.326zM7.994 14.521a6.6 6.6 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.56 6.56 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592m3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.73.73 0 0 0-.529.247c-.182.198-.691.677-.691 1.654s.71 1.916.81 2.049c.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232"/>
    </svg>
    <span class="absolute right-full mr-4 bg-gray-900 text-white text-sm font-bold px-3 py-1.5 rounded shadow-lg opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none border border-gray-700">
        Hubungi Kami
    </span>
</a>

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        once: false,
        offset: 50,
        duration: 800,
    });
</script>