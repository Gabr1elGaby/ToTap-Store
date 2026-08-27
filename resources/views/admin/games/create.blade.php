<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.games.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 text-sm font-bold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-700 transition shadow-sm">
                &larr; Kembali
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ request('category') === 'Aplikasi Premium' ? __('Tambah Layanan Aplikasi Premium') : __('Tambah Game Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen" x-data="gameForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Card Template Otomatis -->
            <div style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #4c1d95 100%); color: #ffffff !important;" class="rounded-3xl p-6 sm:p-7 shadow-xl border border-indigo-500/30">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-white/20 backdrop-blur flex items-center justify-center text-white text-2xl shrink-0 border border-white/30">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-black text-white">Auto-Fill Data Game & Voucher (VIP Payment)</h3>
                        <p class="text-xs text-indigo-100 mt-1 font-medium">
                            Pilih game atau voucher di bawah ini untuk mengisi seluruh formulir secara otomatis dengan format ID, Zone, Publisher, dan panduan yang sesuai.
                        </p>

                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <select x-model="selectedPreset" @change="applyPreset()" style="background-color: rgba(255, 255, 255, 0.2); color: #ffffff;" class="backdrop-blur border border-white/40 rounded-xl px-4 py-2.5 text-xs font-bold focus:ring-2 focus:ring-emerald-400 focus:outline-none cursor-pointer">
                                <option value="" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">-- Pilih Game / Voucher (35+ Game VIP Reseller) --</option>
                                
                                <optgroup label="🎮 Game Mobile Populer" style="color: #111827; background: #ffffff;">
                                    <option value="mlbb" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Mobile Legends: Bang Bang (User ID + Zone ID)</option>
                                    <option value="magic_chess" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Magic Chess: Go Go (User ID + Zone ID)</option>
                                    <option value="free_fire" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Free Fire & FF Max (Player ID)</option>
                                    <option value="pubg" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">PUBG Mobile (Player ID / UID)</option>
                                    <option value="hok" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Honor of Kings (UID)</option>
                                    <option value="genshin" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Genshin Impact (UID + Server)</option>
                                    <option value="hsr" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Honkai: Star Rail (UID + Server)</option>
                                    <option value="zzz" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Zenless Zone Zero (UID + Server)</option>
                                    <option value="codm" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Call of Duty: Mobile (OpenID)</option>
                                    <option value="blood_strike" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Blood Strike (User ID)</option>
                                    <option value="aov" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Arena of Valor (OpenID)</option>
                                    <option value="wild_rift" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">League of Legends: Wild Rift (Riot ID#Tag)</option>
                                    <option value="tft" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Teamfight Tactics (TFT Mobile) (Riot ID#Tag)</option>
                                    <option value="fc_mobile" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">EA SPORTS FC Mobile (UID)</option>
                                    <option value="efootball" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">eFootball 2025 Mobile (User ID)</option>
                                    <option value="metal_slug" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Metal Slug: Awakening (Role ID + Server ID)</option>
                                    <option value="ragnarok_origin" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Ragnarok Origin (Secret ID + Server)</option>
                                    <option value="super_sus" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Super Sus (Space ID)</option>
                                    <option value="eggy_party" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Eggy Party (User ID)</option>
                                    <option value="stumble_guys" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Stumble Guys (Username)</option>
                                    <option value="undawn" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Undawn (Player ID)</option>
                                    <option value="lifeafter" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">LifeAfter (Account ID + Server)</option>
                                    <option value="sausage_man" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Sausage Man (Character ID)</option>
                                    <option value="tof" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Tower of Fantasy (UID + Server)</option>
                                    <option value="higgs_domino" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Higgs Domino Island (User ID)</option>
                                    <option value="roblox" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Roblox (Username)</option>
                                </optgroup>

                                <optgroup label="💻 Game PC" style="color: #111827; background: #ffffff;">
                                    <option value="valorant" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Valorant (Riot ID + Tagline)</option>
                                    <option value="point_blank" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Point Blank Zepetto (User ID)</option>
                                    <option value="steam" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Steam Wallet IDR (Nomor WhatsApp / Voucher)</option>
                                </optgroup>

                                <optgroup label="🎟️ Voucher Game & Console" style="color: #111827; background: #ffffff;">
                                    <option value="gplay" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Google Play Voucher IDR (Nomor WhatsApp)</option>
                                    <option value="psn" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">PlayStation Network (PSN IDR) (Nomor WhatsApp)</option>
                                    <option value="nintendo" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Nintendo eShop Card (Nomor WhatsApp)</option>
                                    <option value="garena_shells" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Garena Shells (Nomor WhatsApp)</option>
                                    <option value="unipin" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">UniPin Voucher IDR (Nomor WhatsApp)</option>
                                    <option value="razer_gold" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Razer Gold IDR (Nomor WhatsApp)</option>
                                </optgroup>

                                <optgroup label="🎬 Apps & Streaming (VIP Reseller)" style="color: #111827; background: #ffffff;">
                                    <option value="alight_motion" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Alight Motion (Email / No WhatsApp)</option>
                                    <option value="amazon_prime" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Amazon Prime Video (Masukan Email)</option>
                                    <option value="bstation" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Bstation Premium (Email / ID)</option>
                                    <option value="canva" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Canva Pro (Masukan Email Canva)</option>
                                    <option value="capcut" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">CapCut Pro (Masukan Email / No HP)</option>
                                    <option value="gemini" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Gemini (Masukan Email Google)</option>
                                    <option value="iqiyi" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">iQIYI Premium (Masukan Email / No HP)</option>
                                    <option value="vidio" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Vidio Premier (Email / No WhatsApp)</option>
                                    <option value="vision_plus" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Vision Plus (Masukan Email / No HP)</option>
                                    <option value="viu" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Viu Premium (Masukan Email / No HP)</option>
                                    <option value="wetv" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">WeTV Premium (Email / No WhatsApp)</option>
                                    <option value="youtube" class="text-gray-900 font-bold" style="color: #111827; background: #ffffff;">Youtube Premium (Masukan Email Google)</option>
                                </optgroup>
                            </select>

                            <button type="button" @click="applyPreset()" x-show="selectedPreset" style="background-color: #10b981; color: #ffffff;" class="px-4 py-2.5 rounded-xl hover:opacity-90 font-black text-xs shadow-lg transition flex items-center gap-1.5 cursor-pointer">
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
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Nama Game <span class="text-red-500">*</span></label>
                            <input type="text" name="name" x-model="name" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold" placeholder="Contoh: Mobile Legends" required>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Developer / Publisher</label>
                            <input type="text" name="developer" x-model="developer" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white" placeholder="Contoh: Moonton / Garena / Riot Games">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Kategori</label>
                            <select name="category" x-model="category" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="Mobile Game">Mobile Game</option>
                                <option value="PC Game">PC Game</option>
                                <option value="Aplikasi Premium">Aplikasi Premium</option>
                                <option value="Voucher">Voucher</option>
                                <option value="App & Entertainment">App & Entertainment (Streaming)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Status Game</label>
                            <select name="is_active" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white font-bold">
                                <option value="1">Aktif (Tampil di Website)</option>
                                <option value="0">Nonaktif (Disembunyikan)</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Upload Thumbnail (Kotak 1:1)</label>
                            <input type="file" name="thumbnail" class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm" accept="image/*">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Upload Cover (Banner Memanjang)</label>
                            <input type="file" name="cover_image" class="w-full mt-1.5 p-2 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm" accept="image/*">
                        </div>
                    </div>

                    <!-- Custom Form Input Fields -->
                    <div class="p-6 bg-slate-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700 space-y-4">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                            <i class="fas fa-id-card text-indigo-500"></i> Data Akun yang Diperlukan Pembeli
                        </h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Target 1 (Data No / ID Utama) <span class="text-red-500">*</span></label>
                                <input type="text" name="target_field_1" x-model="target_field_1" class="w-full mt-1 p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-mono" placeholder="Contoh: User ID / Player ID / Riot ID / No WhatsApp" required>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-700 dark:text-gray-300">Label Target 2 (Data Zone / Server - Opsional)</label>
                                <input type="text" name="target_field_2" x-model="target_field_2" class="w-full mt-1 p-2.5 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm font-mono" placeholder="Contoh: Zone ID / Server ID (Kosongkan jika tidak ada)">
                            </div>
                        </div>

                        <div class="pt-2">
                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="requires_zone_id" x-model="requires_zone_id" value="1" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 focus:ring-indigo-500">
                                <span class="text-sm font-bold text-gray-900 dark:text-gray-100">Game Membutuhkan Zone ID / Server ID Terpisah</span>
                            </label>
                            <p class="text-xs text-gray-600 dark:text-gray-300 mt-1">Centang jika game memerlukan 2 kolom input terpisah (seperti Mobile Legends / Genshin Impact). Jangan dicentang jika game hanya butuh 1 ID atau Nomor WhatsApp (seperti Free Fire, Valorant, atau Steam Wallet).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-900 dark:text-gray-100">Panduan / Petunjuk Menemukan ID Game</label>
                        <textarea name="guide_text" x-model="guide_text" rows="3" class="w-full mt-1.5 p-3 rounded-xl border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white text-sm" placeholder="Contoh: Untuk menemukan User ID dan Zone ID Anda, buka profil in-game di pojok kiri atas..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.games.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-300 dark:border-gray-700 font-bold text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm shadow-md shadow-indigo-600/30 transition">
                            Simpan Game Baru
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function gameForm() {
            const urlParams = new URLSearchParams(window.location.search);
            const defaultCategory = urlParams.get('category') || 'Mobile Game';
            const defaultField1 = defaultCategory === 'Aplikasi Premium' ? 'Nomor WhatsApp / Akun' : 'User ID';
            
            return {
                selectedPreset: '',
                name: '',
                developer: '',
                category: defaultCategory,
                target_field_1: defaultField1,
                target_field_2: '',
                requires_zone_id: false,
                guide_text: '',
                presets: {
                    // Mobile Games
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
                    hok: {
                        name: 'Honor of Kings',
                        developer: 'Level Infinite',
                        category: 'Mobile Game',
                        target_field_1: 'UID Honor of Kings',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil Honor of Kings Anda, salin nomor UID akun pada menu Pengaturan Akun.'
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
                    zzz: {
                        name: 'Zenless Zone Zero',
                        developer: 'COGNOSPHERE',
                        category: 'Mobile Game',
                        target_field_1: 'UID Zenless Zone Zero',
                        target_field_2: 'Server (Asia/America/Europe/TW_HK_MO)',
                        requires_zone_id: true,
                        guide_text: 'Masukkan UID ZZZ yang tertera pada kartu profil game dan pilih Server Anda.'
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
                    wild_rift: {
                        name: 'League of Legends: Wild Rift',
                        developer: 'Riot Games',
                        category: 'Mobile Game',
                        target_field_1: 'Riot ID (Username#TAG)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Riot ID lengkap dengan tanda pagar (#), contoh: Yasuo#ID1.'
                    },
                    tft: {
                        name: 'Teamfight Tactics Mobile',
                        developer: 'Riot Games',
                        category: 'Mobile Game',
                        target_field_1: 'Riot ID (Username#TAG)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Riot ID akun TFT Anda lengkap dengan tag (#), contoh: Player#1234.'
                    },
                    fc_mobile: {
                        name: 'EA SPORTS FC Mobile',
                        developer: 'Electronic Arts (EA)',
                        category: 'Mobile Game',
                        target_field_1: 'UID FC Mobile',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka Pengaturan FC Mobile > Akun > Salin UID akun Anda.'
                    },
                    efootball: {
                        name: 'eFootball 2025 Mobile',
                        developer: 'KONAMI',
                        category: 'Mobile Game',
                        target_field_1: 'User ID eFootball',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka Menu Dukungan Ekstra in-game > Detail Pengguna > Salin User ID Anda.'
                    },
                    metal_slug: {
                        name: 'Metal Slug: Awakening',
                        developer: 'VNG Games',
                        category: 'Mobile Game',
                        target_field_1: 'Role ID (UID)',
                        target_field_2: 'Server ID',
                        requires_zone_id: true,
                        guide_text: 'Salin Role ID pada profil avatar dan masukkan nomor Server ID Metal Slug Anda.'
                    },
                    ragnarok_origin: {
                        name: 'Ragnarok Origin Global',
                        developer: 'Gravity',
                        category: 'Mobile Game',
                        target_field_1: 'Secret ID Karakter',
                        target_field_2: 'Server Name',
                        requires_zone_id: true,
                        guide_text: 'Salin Secret ID karakter dari menu Pengaturan Akun dan pilih Server Anda.'
                    },
                    super_sus: {
                        name: 'Super Sus',
                        developer: 'PIProduction',
                        category: 'Mobile Game',
                        target_field_1: 'Space ID',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil Super Sus Anda di pojok kiri atas dan salin Space ID akun.'
                    },
                    eggy_party: {
                        name: 'Eggy Party',
                        developer: 'NetEase Games',
                        category: 'Mobile Game',
                        target_field_1: 'User ID Eggy Party',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Salin nomor User ID yang tertera pada profil avatar Eggy Party Anda.'
                    },
                    stumble_guys: {
                        name: 'Stumble Guys',
                        developer: 'Scopely',
                        category: 'Mobile Game',
                        target_field_1: 'Username In-Game',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Username akun Stumble Guys Anda dengan tepat.'
                    },
                    undawn: {
                        name: 'Undawn',
                        developer: 'Garena',
                        category: 'Mobile Game',
                        target_field_1: 'Player ID Undawn',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil Undawn di pojok kiri atas, salin Player ID akun Anda.'
                    },
                    lifeafter: {
                        name: 'LifeAfter',
                        developer: 'NetEase Games',
                        category: 'Mobile Game',
                        target_field_1: 'Account ID LifeAfter',
                        target_field_2: 'Server Name',
                        requires_zone_id: true,
                        guide_text: 'Salin Account ID dari menu profil dan pilih Server LifeAfter Anda.'
                    },
                    sausage_man: {
                        name: 'Sausage Man',
                        developer: 'XD Entertainment',
                        category: 'Mobile Game',
                        target_field_1: 'Character ID',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Salin 6-8 digit Character ID pada menu profil avatar Sausage Man Anda.'
                    },
                    tof: {
                        name: 'Tower of Fantasy',
                        developer: 'Level Infinite',
                        category: 'Mobile Game',
                        target_field_1: 'UID Tower of Fantasy',
                        target_field_2: 'Server Name',
                        requires_zone_id: true,
                        guide_text: 'Salin UID akun dan pilih Server Tower of Fantasy Anda.'
                    },
                    higgs_domino: {
                        name: 'Higgs Domino Island',
                        developer: 'Higgs Games',
                        category: 'Mobile Game',
                        target_field_1: 'User ID Higgs Domino',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Buka profil avatar Higgs Domino Anda di pojok kiri atas dan salin deretan angka User ID.'
                    },
                    roblox: {
                        name: 'Roblox',
                        developer: 'Roblox Corporation',
                        category: 'Mobile Game',
                        target_field_1: 'Username Roblox',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Username resmi akun Roblox Anda (bukan Display Name).'
                    },

                    // PC Games
                    valorant: {
                        name: 'Valorant',
                        developer: 'Riot Games',
                        category: 'PC Game',
                        target_field_1: 'Riot ID (Username#TAG)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Riot ID lengkap dengan tanda pagar (#), contoh: Jett#1234.'
                    },
                    point_blank: {
                        name: 'Point Blank',
                        developer: 'Zepetto',
                        category: 'PC Game',
                        target_field_1: 'User ID Zepetto',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan User ID / Login ID akun Zepetto Point Blank Anda.'
                    },
                    steam: {
                        name: 'Steam Wallet IDR',
                        developer: 'Valve Corporation',
                        category: 'PC Game',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda. Kode Voucher Steam Wallet (Serial Number) akan dikirimkan dan tampil otomatis pada invoice setelah pembayaran berhasil.'
                    },

                    // Voucher & Gift Cards
                    gplay: {
                        name: 'Google Play Voucher IDR',
                        developer: 'Google LLC',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda. Kode Voucher Google Play akan dikirimkan otomatis setelah transaksi sukses.'
                    },
                    psn: {
                        name: 'PlayStation Network (PSN Card IDR)',
                        developer: 'Sony PlayStation',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda untuk menerima kode voucher PSN Card.'
                    },
                    nintendo: {
                        name: 'Nintendo eShop Card',
                        developer: 'Nintendo',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda untuk menerima kode voucher Nintendo eShop.'
                    },
                    garena_shells: {
                        name: 'Garena Shells',
                        developer: 'Garena',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda untuk menerima serial kode voucher Garena Shells.'
                    },
                    unipin: {
                        name: 'UniPin Voucher IDR',
                        developer: 'UniPin',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda untuk menerima serial kode voucher UniPin.'
                    },
                    razer_gold: {
                        name: 'Razer Gold IDR',
                        developer: 'Razer Inc.',
                        category: 'Voucher',
                        target_field_1: 'Nomor WhatsApp / No HP',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Nomor WhatsApp Anda untuk menerima serial kode voucher Razer Gold.'
                    },

                    // Apps & Streaming (Sesuai 100% dengan Kategori Apps & Streaming di VIP Reseller)
                    alight_motion: {
                        name: 'Alight Motion',
                        developer: 'Alight Creative, Inc.',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No WhatsApp',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau Nomor WhatsApp Anda untuk aktivasi akun Alight Motion Pro.'
                    },
                    amazon_prime: {
                        name: 'Amazon Prime Video',
                        developer: 'Amazon.com, Inc.',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email',
                        target_field_2: 'Request Profile',
                        requires_zone_id: false,
                        guide_text: 'Masukkan alamat email aktif Anda. Akun atau link invite akan dikirim melalui riwayat pesanan (invoice) atau email Anda.'
                    },
                    bstation: {
                        name: 'Bstation Premium',
                        developer: 'Bilibili Pte. Ltd.',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / User ID Bstation',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau User ID akun Bstation Anda untuk aktivasi langganan Bstation Premium.'
                    },
                    canva: {
                        name: 'Canva Pro',
                        developer: 'Canva Pty Ltd',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email Canva',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan email akun Canva Anda. Undangan tim/organisasi Canva Pro akan dikirimkan langsung ke email Anda.'
                    },
                    capcut: {
                        name: 'CapCut Pro',
                        developer: 'ByteDance Ltd.',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No HP Akun',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau Nomor HP akun CapCut Anda untuk aktivasi langganan CapCut Pro.'
                    },
                    gemini: {
                        name: 'Gemini',
                        developer: 'Google LLC',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email Google (Gmail)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan alamat Email Google (Gmail) aktif Anda untuk aktivasi langganan Google Gemini Advanced.'
                    },
                    iqiyi: {
                        name: 'iQIYI Premium',
                        developer: 'iQIYI International',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No HP Akun',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau No HP akun iQIYI Anda untuk aktivasi akun iQIYI VIP / Premium.'
                    },
                    vidio: {
                        name: 'Vidio Premier',
                        developer: 'PT Vidio Dot Com',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No WhatsApp',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau Nomor WhatsApp Anda untuk pengiriman voucher/aktivasi paket Vidio Premier.'
                    },
                    vision_plus: {
                        name: 'Vision Plus',
                        developer: 'MNC Group / Vision+',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No HP Akun',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau No HP akun Vision+ Anda untuk aktivasi langganan paket Vision Plus.'
                    },
                    viu: {
                        name: 'Viu Premium',
                        developer: 'Viu International Ltd.',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No HP Akun',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau No HP akun Viu Anda untuk aktivasi langganan Viu Premium.'
                    },
                    wetv: {
                        name: 'WeTV Premium',
                        developer: 'Image Future / Tencent',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Masukan Email / No WhatsApp',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan Email atau Nomor WhatsApp Anda untuk aktivasi akun WeTV VIP / Premium.'
                    },
                    youtube: {
                        name: 'Youtube Premium',
                        developer: 'Google LLC',
                        category: 'Aplikasi Premium',
                        target_field_1: 'Email Google (Gmail)',
                        target_field_2: '',
                        requires_zone_id: false,
                        guide_text: 'Masukkan alamat Email Google (Gmail) aktif Anda. Undangan YouTube Family akan dikirim langsung ke Gmail Anda.'
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