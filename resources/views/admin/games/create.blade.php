<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-700 transition shadow-sm">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tambah Game Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen" x-data="gameForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Template Otomatis -->
            <div class="bg-gradient-to-r from-indigo-900 via-indigo-800 to-purple-900 rounded-3xl p-6 sm:p-7 text-white shadow-xl border border-indigo-700/50">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center text-indigo-200 text-2xl shrink-0 border border-white/20">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-white">Auto-Fill Data Game Populer</h3>
                        <p class="text-xs text-indigo-200 mt-1">
                            Pilih game di bawah ini untuk mengisi seluruh data yang diperlukan secara otomatis (Format User ID, Zone ID, Publisher, dan Panduan Top Up).
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <select x-model="selectedPreset" @change="applyPreset()" class="bg-white/10 backdrop-blur border border-white/30 text-white rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                                <option value="" class="text-gray-900 font-bold">-- Pilih Game Populer (Auto-Fill) --</option>
                                <option value="mlbb" class="text-gray-900 font-bold">Mobile Legends (User ID + Zone ID)</option>
                                <option value="magic_chess" class="text-gray-900 font-bold">Magic Chess: Go Go (User ID + Zone ID)</option>
                                <option value="free_fire" class="text-gray-900 font-bold">Free Fire (Player ID)</option>
                                <option value="pubg" class="text-gray-900 font-bold">PUBG Mobile (Player ID)</option>
                                <option value="valorant" class="text-gray-900 font-bold">Valorant (Riot ID + Tagline)</option>
                                <option value="genshin" class="text-gray-900 font-bold">Genshin Impact (UID + Server)</option>
                                <option value="hsr" class="text-gray-900 font-bold">Honkai: Star Rail (UID + Server)</option>
                                <option value="codm" class="text-gray-900 font-bold">Call of Duty: Mobile (OpenID)</option>
                                <option value="hok" class="text-gray-900 font-bold">Honor of Kings (UID)</option>
                                <option value="blood_strike" class="text-gray-900 font-bold">Blood Strike (User ID)</option>
                                <option value="aov" class="text-gray-900 font-bold">Arena of Valor (OpenID)</option>
                                <option value="point_blank" class="text-gray-900 font-bold">Point Blank (Zepetto ID)</option>
                                <option value="roblox" class="text-gray-900 font-bold">Roblox (Username)</option>
                            </select>

                            <button type="button" @click="applyPreset()" x-show="selectedPreset" class="px-4 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-gray-950 font-black text-xs shadow transition flex items-center gap-1.5 cursor-pointer">
                                <i class="fas fa-check"></i> Terapkan Format
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-3xl border border-gray-200 dark:border-gray-700 p-8">
                <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Nama Game <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="name" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold" required placeholder="Contoh: Mobile Legends">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Developer / Publisher</label>
                            <input type="text" name="developer" x-model="developer" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Moonton / Garena / Riot Games">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Kategori</label>
                            <select name="category" x-model="category" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="Mobile Game">Mobile Game</option>
                                <option value="PC Game">PC Game</option>
                                <option value="Voucher">Voucher</option>
                                <option value="App & Entertainment">App & Entertainment</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Status Game</label>
                            <select name="is_active" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="1">Aktif (Tampil di Website)</option>
                                <option value="0">Sembunyikan / Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload Gambar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Thumbnail (Kotak 1:1)</label>
                            <input type="file" name="thumbnail" accept="image/*" 
                                   onchange="const [file] = this.files; if(file){ const p = document.getElementById('thumb-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                                   class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs">
                            <img id="thumb-preview" src="" class="h-20 w-20 object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 hidden">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Upload Cover (Banner Memanjang)</label>
                            <input type="file" name="cover_image" accept="image/*" 
                                   onchange="const [file] = this.files; if(file){ const p = document.getElementById('cover-preview'); p.src = URL.createObjectURL(file); p.classList.remove('hidden'); }"
                                   class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-xs">
                            <img id="cover-preview" src="" class="h-20 w-full max-w-xs object-cover mt-2.5 rounded-xl border border-gray-300 dark:border-gray-600 hidden">
                        </div>
                    </div>

                    <!-- Data Target / Akun yang Diperlukan -->
                    <div class="p-5 rounded-2xl bg-slate-50 dark:bg-gray-900/60 border border-gray-200 dark:border-gray-700 space-y-4">
                        <div class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-500"></i>
                            Data Akun yang Diperlukan Pembeli
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Input 1 (Data Akun Utama) <span class="text-red-500">*</span></label>
                                <input type="text" name="target_field_1" x-model="target_field_1" placeholder="Contoh: User ID / Player ID" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-semibold" required>
                            </div>
                            <div x-show="requires_zone_id">
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Input 2 (Zone / Server ID)</label>
                                <input type="text" name="target_field_2" x-model="target_field_2" placeholder="Contoh: Zone ID / Server" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-semibold">
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <input type="checkbox" name="requires_zone_id" id="requires_zone_id" x-model="requires_zone_id" value="1" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500 border-gray-300 cursor-pointer">
                            <label for="requires_zone_id" class="text-xs font-bold text-gray-700 dark:text-gray-300 cursor-pointer">
                                Game ini membutuhkan 2 kolom input (seperti Mobile Legends / Genshin Impact)
                            </label>
                        </div>
                    </div>

                    <!-- Panduan / Petunjuk Top Up -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Panduan / Cara Cek ID Akun</label>
                        <textarea name="guide_text" x-model="guide_text" rows="3" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-medium" placeholder="Tuliskan petunjuk cara melihat ID game..."></textarea>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.games.index') }}" class="px-5 py-3 rounded-xl bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-bold text-xs transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-indigo-600/30 transition cursor-pointer flex items-center gap-2 text-sm">
                            <i class="fas fa-save"></i> Simpan Game Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function gameForm() {
            return {
                selectedPreset: '',
                name: '',
                developer: '',
                category: 'Mobile Game',
                target_field_1: 'User ID',
                target_field_2: '',
                requires_zone_id: false,
                guide_text: '',
                presets: {
                    mlbb: {
                        name: 'Mobile Legends',
                        developer: 'Moonton',
                        category: 'Mobile Game',
                        target_field_1: 'User ID',
                        target_field_2: 'Zone ID',
                        requires_zone_id: true,
                        guide_text: 'Untuk menemukan User ID dan Zone ID Anda, buka profil in-game di pojok kiri atas. Contoh: 12345678 (1234).'
                    },
                    magic_chess: {
                        name: 'Magic Chess: Go Go',
                        developer: 'Moonton',
                        category: 'Mobile Game',
                        target_field_1: 'User ID',
                        target_field_2: 'Zone ID',
                        requires_zone_id: true,
                        guide_text: 'Masukkan User ID dan Zone ID akun Magic Chess: Go Go Anda yang tertera di menu profil game.'
                    },
                    free_fire: {
                        name: 'Free Fire',
                        developer: 'Garena',
                        category: 'Mobile Game',
                        target_field_1: 'Player ID',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil Free Fire Anda, salin 9-10 digit Player ID pada menu profil akun.'
                    },
                    pubg: {
                        name: 'PUBG Mobile',
                        developer: 'Tencent Games',
                        category: 'Mobile Game',
                        target_field_1: 'Player ID (UID)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil PUBG Mobile Anda di pojok kiri atas, salin deretan angka Player ID di samping avatar.'
                    },
                    valorant: {
                        name: 'Valorant',
                        developer: 'Riot Games',
                        category: 'PC Game',
                        target_field_1: 'Riot ID (Username#TAG)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Riot ID lengkap dengan tanda pagar (#), contoh: Jett#1234.'
                    },
                    genshin: {
                        name: 'Genshin Impact',
                        developer: 'COGNOSPHERE',
                        category: 'Mobile Game',
                        target_field_1: 'UID Genshin Impact',
                        target_field_2: 'Server (Asia/America/Europe/TW_HK_MO)',
                        requires_zone_id: true,
                        guide_text: 'Masukkan 9 digit UID Genshin Impact yang tertera di pojok kanan bawah layar dan pilih Server Anda.'
                    },
                    hsr: {
                        name: 'Honkai: Star Rail',
                        developer: 'COGNOSPHERE',
                        category: 'Mobile Game',
                        target_field_1: 'UID Honkai: Star Rail',
                        target_field_2: 'Server (Asia/America/Europe/TW_HK_MO)',
                        requires_zone_id: true,
                        guide_text: 'Masukkan 9 digit UID Honkai: Star Rail yang tertera di menu ponsel in-game dan pilih Server Anda.'
                    },
                    codm: {
                        name: 'Call of Duty: Mobile',
                        developer: 'Garena',
                        category: 'Mobile Game',
                        target_field_1: 'OpenID CODM',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka menu Pengaturan in-game CODM > Lainnya > Salin nomor OpenID Anda.'
                    },
                    hok: {
                        name: 'Honor of Kings',
                        developer: 'Level Infinite',
                        category: 'Mobile Game',
                        target_field_1: 'UID Honor of Kings',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil Honor of Kings Anda, salin nomor UID akun pada menu Pengaturan Akun.'
                    },
                    blood_strike: {
                        name: 'Blood Strike',
                        developer: 'NetEase Games',
                        category: 'Mobile Game',
                        target_field_1: 'User ID Blood Strike',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Salin User ID numerik pada tab profil game Blood Strike Anda.'
                    },
                    aov: {
                        name: 'Arena of Valor',
                        developer: 'Garena',
                        category: 'Mobile Game',
                        target_field_1: 'OpenID AOV',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka menu Pengaturan AOV > Umum > Salin OpenID Anda.'
                    },
                    point_blank: {
                        name: 'Point Blank',
                        developer: 'Zepetto',
                        category: 'PC Game',
                        target_field_1: 'User ID Zepetto',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan User ID akun Zepetto Point Blank Anda.'
                    },
                    roblox: {
                        name: 'Roblox',
                        developer: 'Roblox Corporation',
                        category: 'Mobile Game',
                        target_field_1: 'Username Roblox',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Username resmi akun Roblox Anda (bukan Display Name).'
                    }
                },
                applyPreset() {
                    if (this.selectedPreset && this.presets[this.selectedPreset]) {
                        const p = this.presets[this.selectedPreset];
                        this.name = p.name;
                        this.developer = p.developer;
                        this.category = p.category;
                        this.target_field_1 = p.target_field_1;
                        this.target_field_2 = p.target_field_2;
                        this.requires_zone_id = p.requires_zone_id;
                        this.guide_text = p.guide_text;
                    }
                }
            };
        }
    </script>
</x-app-layout>