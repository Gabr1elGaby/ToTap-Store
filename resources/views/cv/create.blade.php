<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lengkapi Data CV - ToTap Store</title>
    <link rel="icon" href="{{ asset('images/logo-totap-v2.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .preview-container {
            transform-origin: top left;
        }
    </style>
    <style>
        /* Fallback Tailwind JIT classes */
        @media (min-width: 768px) {
            .md\:hidden { display: none !important; }
            .md\:flex { display: flex !important; }
            .md\:h-screen { height: 100vh !important; }
            .md\:overflow-hidden { overflow: hidden !important; }
            .md\:overflow-y-auto { overflow-y: auto !important; }
            .md\:flex-row { flex-direction: row !important; }
            .md\:w-\[400px\] { width: 400px !important; }
            .md\:p-8 { padding: 2rem !important; }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased md:overflow-hidden md:h-screen flex flex-col" 
      x-data="cvForm('{{ $template->slug }}', {{ $template->id }}, {{ $template->price }})">
    
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200 shrink-0 shadow-sm z-10 relative">
        <div class="px-3 sm:px-6 h-14 flex justify-between items-center">
            <div class="flex items-center gap-2 sm:gap-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                    <img src="{{ asset('images/logo-totap-v2.png') }}" alt="ToTap Store" class="h-8 w-auto object-contain drop-shadow-sm group-hover:scale-105 transition-transform">
                    <span class="font-extrabold text-gray-900 text-sm hidden sm:inline">ToTap Store</span>
                </a>
                <span class="text-gray-300">|</span>
                <a href="{{ route('cv.index') }}" class="text-gray-500 hover:text-gray-900 transition flex items-center gap-1 text-xs sm:text-sm font-semibold shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    <span>Template</span>
                </a>
                <span class="text-gray-300 hidden md:inline">|</span>
                <span class="font-bold text-gray-700 text-xs sm:text-sm hidden md:inline truncate bg-gray-100 px-2 py-0.5 rounded">Template: {{ $template->name }}</span>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                <span class="text-xs sm:text-sm font-medium text-gray-500">Harga: <strong class="text-blue-600 font-bold">Rp{{ number_format($template->price, 0, ',', '.') }}</strong></span>
                <button @click="checkout" :disabled="loading" class="bg-blue-600 text-white px-3 sm:px-4 py-1.5 rounded-lg text-xs sm:text-sm font-bold shadow-sm hover:bg-blue-700 transition disabled:opacity-50 shrink-0 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span x-show="!loading">Lanjut Pembayaran</span>
                    <span x-show="loading">...</span>
                </button>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 md:overflow-hidden relative flex-col md:flex-row">
        
        <!-- Left Panel: Form -->
        <div class="w-full md:w-[400px] bg-white border-r border-gray-200 flex flex-col z-10 relative shadow-[4px_0_24px_rgba(0,0,0,0.02)] shrink-0">
            <!-- Progress Bar -->
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 shrink-0">
                <div class="flex justify-between items-center mb-2 mt-4" x-show="steps.length > 0">
                    <span class="text-xs font-bold text-gray-500 uppercase tracking-widest" x-text="steps[currentStep].title"></span>
                    <span class="text-xs font-bold text-gray-400" x-text="(currentStep + 1) + ' / ' + steps.length"></span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-1.5">
                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" :style="`width: ${((currentStep + 1) / steps.length) * 100}%`"></div>
                </div>
            </div>

            <!-- Form Content (Scrollable) -->
            <div class="flex-1 md:overflow-y-auto p-4 sm:p-6 scroll-smooth" id="form-container">
                
                <!-- STEP: Data Pribadi -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'pribadi'" x-transition.opacity>
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Pribadi</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap *</label>
                            <input type="text" x-model="data.name" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="John Doe">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Posisi / Job Title</label>
                            <input type="text" x-model="data.job_title" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Software Engineer">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Email *</label>
                                <input type="email" x-model="data.email" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="john@example.com">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Nomor HP</label>
                                <input type="text" x-model="data.phone" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="08123456789">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Alamat (Kota, Provinsi)</label>
                            <input type="text" x-model="data.address" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Jakarta, Indonesia">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">LinkedIn</label>
                                <input type="text" x-model="data.linkedin" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="linkedin.com/in/johndoe">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-700 mb-1">Website / Portofolio</label>
                                <input type="text" x-model="data.website" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="johndoe.com">
                            </div>
                        </div>
                        <div x-show="templateSlug === 'creative'">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Social Media Profesional</label>
                            <input type="text" x-model="data.social_media" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="instagram.com/johndoe">
                        </div>
                        <div x-show="templateSlug !== 'ats'">
                            <label class="block text-xs font-bold text-gray-700 mb-1">Upload Foto (Opsional)</label>
                            <input type="file" @change="handlePhotoUpload" accept="image/*" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500 text-gray-500">
                        </div>
                    </div>
                </div>

                <!-- STEP: Profil Singkat -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'profil'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Profil Singkat</h3>
                    <p class="text-xs text-gray-500 mb-4">Tuliskan 2-3 kalimat yang mendeskripsikan diri Anda, tujuan karir, dan keahlian utama.</p>
                    <div>
                        <textarea x-model="data.profile" @input.debounce.500ms="updatePreview" rows="6" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Profesional dengan pengalaman dalam bidang..."></textarea>
                    </div>
                </div>

                <!-- STEP: Pendidikan -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'pendidikan'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pendidikan</h3>
                    
                    <template x-for="(edu, index) in data.educations" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('educations', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Institusi / Universitas / Sekolah</label>
                                    <input type="text" x-model="edu.institution" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="Universitas Indonesia">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Jurusan</label>
                                        <input type="text" x-model="edu.major" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="Teknik Informatika">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Gelar</label>
                                        <input type="text" x-model="edu.degree" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="S1, D3, SMA, dll">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Mulai</label>
                                        <input type="text" x-model="edu.start_year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="2018">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Selesai</label>
                                        <input type="text" x-model="edu.end_year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="2022">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi / Prestasi / Nilai IPK (Opsional)</label>
                                    <textarea x-model="edu.description" @input.debounce.500ms="updatePreview" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="Contoh: Lulus dengan predikat Cum Laude (IPK 3.85/4.00), aktif dalam organisasi kemahasiswaan."></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <button @click="addArrayItem('educations', {institution:'', major:'', degree:'', start_year:'', end_year:'', description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition flex items-center justify-center gap-2">
                        <span>+ Tambah Pendidikan</span>
                    </button>
                </div>

                <!-- STEP: Pengalaman Kerja -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'pengalaman'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengalaman Kerja</h3>
                    
                    <template x-for="(exp, index) in data.experiences" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('experiences', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Perusahaan</label>
                                    <input type="text" x-model="exp.company" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Posisi</label>
                                        <input type="text" x-model="exp.position" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Lokasi</label>
                                        <input type="text" x-model="exp.location" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="Jakarta">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Mulai</label>
                                        <input type="text" x-model="exp.start_year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="Jan 2020">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun Selesai</label>
                                        <input type="text" x-model="exp.end_year" :disabled="exp.is_current" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm disabled:bg-gray-100 disabled:text-gray-400" placeholder="Sekarang">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" x-model="exp.is_current" @change="updatePreview" class="rounded text-blue-600 focus:ring-blue-500 h-4 w-4">
                                    <label class="text-xs font-bold text-gray-700">Masih bekerja di sini</label>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi & Pencapaian</label>
                                    <textarea x-model="exp.description" @input.debounce.500ms="updatePreview" rows="3" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    
                    <button @click="addArrayItem('experiences', {company:'', position:'', location:'', start_year:'', end_year:'', is_current:false, description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition flex items-center justify-center gap-2">
                        <span>+ Tambah Pengalaman Kerja</span>
                    </button>
                </div>

                <!-- STEP: Magang -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'magang'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengalaman Magang (Internship)</h3>
                    <p class="text-xs text-gray-500 mb-4">Tambahkan pengalaman magang Anda jika ada.</p>
                    <template x-for="(intern, index) in data.internships" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('internships', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Perusahaan/Instansi</label>
                                    <input type="text" x-model="intern.company" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Posisi Magang</label>
                                    <input type="text" x-model="intern.position" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Mulai</label>
                                        <input type="text" x-model="intern.start_year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Selesai</label>
                                        <input type="text" x-model="intern.end_year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Tugas</label>
                                    <textarea x-model="intern.description" @input.debounce.500ms="updatePreview" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="addArrayItem('internships', {company:'', position:'', start_year:'', end_year:'', description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition">
                        + Tambah Pengalaman Magang
                    </button>
                </div>

                <!-- STEP: Organisasi & Kepanitiaan -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'organisasi'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengalaman Organisasi & Kepanitiaan</h3>
                    <template x-for="(org, index) in data.organizations" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('organizations', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Organisasi/Kepanitiaan</label>
                                    <input type="text" x-model="org.organization_name" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Jabatan</label>
                                        <input type="text" x-model="org.role" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Periode</label>
                                        <input type="text" x-model="org.period" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" placeholder="2020 - 2021">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Kegiatan</label>
                                    <textarea x-model="org.description" @input.debounce.500ms="updatePreview" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="addArrayItem('organizations', {organization_name:'', role:'', period:'', description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition">
                        + Tambah Organisasi / Kepanitiaan
                    </button>
                </div>

                <!-- STEP: Project / Portfolio -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'project'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Project / Portfolio</h3>
                    <template x-for="(proj, index) in data.projects" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('projects', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Project</label>
                                    <input type="text" x-model="proj.name" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Peran / Role</label>
                                        <input type="text" x-model="proj.role" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                                        <input type="text" x-model="proj.year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi Project</label>
                                    <textarea x-model="proj.description" @input.debounce.500ms="updatePreview" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="addArrayItem('projects', {name:'', role:'', year:'', description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition">
                        + Tambah Project
                    </button>
                </div>

                <!-- STEP: Skill & Tools -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'skill'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Keahlian (Skills & Tools)</h3>
                    
                        <label class="block text-xs font-bold text-gray-700 mb-2 mt-4">Hard Skills (Technical)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(skill, index) in data.skills" :key="'h'+index">
                                <span x-show="skill.level" class="bg-blue-100 text-blue-800 border border-blue-200 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                                    <span x-text="skill.name"></span>
                                    <span class="font-bold text-[10px]" x-text="skill.level + '%'"></span>
                                    <button @click="data.skills.splice(index, 1); updatePreview()" class="text-blue-400 hover:text-red-500">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2 mb-6">
                            <input type="text" x-model="newHardSkill" @keydown.enter.prevent="addHardSkill" placeholder="Ketik hard skill (cth: SEO)" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <select x-model="newHardSkillLevel" class="border border-gray-300 rounded px-3 py-2 text-sm shrink-0 w-full sm:w-auto">
                                <option value="100">Pakar (100%)</option>
                                <option value="80">Mahir (80%)</option>
                                <option value="60">Menengah (60%)</option>
                                <option value="40">Dasar (40%)</option>
                                <option value="20">Pemula (20%)</option>
                            </select>
                            <button @click.prevent="addHardSkill" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 shrink-0 w-full sm:w-auto whitespace-nowrap">Tambah</button>
                        </div>

                        <label class="block text-xs font-bold text-gray-700 mb-2">Soft Skills (Tanpa Level)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(skill, index) in data.skills" :key="'s'+index">
                                <span x-show="!skill.level" class="bg-green-100 text-green-800 border border-green-200 px-3 py-1 rounded-full text-xs flex items-center gap-2">
                                    <span x-text="skill.name"></span>
                                    <button @click="data.skills.splice(index, 1); updatePreview()" class="text-green-400 hover:text-red-500">&times;</button>
                                </span>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" x-model="newSoftSkill" @keydown.enter.prevent="addSoftSkill" placeholder="Ketik soft skill (cth: Leadership)" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button @click.prevent="addSoftSkill" class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-bold hover:bg-blue-700 shrink-0 w-full sm:w-auto whitespace-nowrap">Tambah</button>
                        </div>

                    <div class="mt-6">
                        <label class="block text-xs font-bold text-gray-700 mb-2">Tools / Software (Aplikasi & Perangkat Lunak)</label>
                        <div class="flex flex-wrap gap-2 mb-2">
                            <template x-for="(tool, index) in data.tools" :key="index">
                                <div class="bg-teal-50 border border-teal-200 text-teal-800 text-xs font-medium px-3 py-1 rounded-full flex items-center gap-2">
                                    <span x-text="tool.name"></span>
                                    <button @click="removeArrayItem('tools', index)" class="text-teal-400 hover:text-teal-600 font-bold">×</button>
                                </div>
                            </template>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="text" x-model="newTool" @keydown.enter.prevent="addTool" class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Ketik tool (cth: Figma, VS Code, Canva)">
                            <button @click.prevent="addTool" type="button" class="bg-teal-600 text-white px-4 py-2 rounded text-sm font-bold shadow-sm hover:bg-teal-700 shrink-0 w-full sm:w-auto whitespace-nowrap">Tambah</button>
                        </div>
                    </div>
                </div>

                <!-- STEP: Sertifikat & Prestasi -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'sertifikat'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Sertifikat & Prestasi (Opsional)</h3>
                    <template x-for="(cert, index) in data.certificates" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('certificates', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Sertifikat/Penghargaan</label>
                                    <input type="text" x-model="cert.name" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Penerbit</label>
                                        <input type="text" x-model="cert.issuer" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                                        <input type="text" x-model="cert.year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="addArrayItem('certificates', {name:'', issuer:'', year:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition">
                        + Tambah Sertifikat/Prestasi
                    </button>
                </div>

                <!-- STEP: Volunteer (Only for Student) -->
                <div x-show="steps.length > 0 && steps[currentStep].id === 'volunteer'" x-transition.opacity style="display: none;">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengalaman Volunteer (Relawan)</h3>
                    <template x-for="(vol, index) in data.volunteers" :key="index">
                        <div class="p-4 border border-gray-200 rounded mb-4 bg-gray-50 relative group">
                            <button @click="removeArrayItem('volunteers', index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 transition opacity-0 group-hover:opacity-100">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Nama Kegiatan/Organisasi</label>
                                    <input type="text" x-model="vol.name" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Peran</label>
                                        <input type="text" x-model="vol.role" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-700 mb-1">Tahun</label>
                                        <input type="text" x-model="vol.year" @input.debounce.500ms="updatePreview" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-700 mb-1">Deskripsi</label>
                                    <textarea x-model="vol.description" @input.debounce.500ms="updatePreview" rows="2" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
                                </div>
                            </div>
                        </div>
                    </template>
                    <button @click="addArrayItem('volunteers', {name:'', role:'', year:'', description:''})" class="w-full border-2 border-dashed border-gray-300 rounded py-2 text-sm font-bold text-blue-600 hover:border-blue-500 transition">
                        + Tambah Volunteer
                    </button>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-8 pt-6 pb-12 border-t border-gray-200 flex justify-between">
                    <button @click="prevStep" :class="{'invisible': currentStep === 0}" class="px-4 py-2 border border-gray-300 rounded text-sm font-bold text-gray-700 hover:bg-gray-50 transition">
                        &larr; Sebelumnya
                    </button>
                    <button @click="nextStep" x-show="currentStep < steps.length - 1" class="px-6 py-2 bg-gray-900 rounded text-sm font-bold text-white hover:bg-black transition shadow-md">
                        Selanjutnya &rarr;
                    </button>
                    <button @click="checkout" x-show="currentStep === steps.length - 1" class="px-6 py-2 bg-blue-600 rounded text-sm font-bold text-white hover:bg-blue-700 transition shadow-md flex items-center gap-2">
                        <span x-show="!loading">Selesai & Bayar</span>
                        <svg x-show="loading" class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </button>
                </div>
            </div> <!-- Closes form-container -->
        </div> <!-- Closes left panel -->
        
        <!-- Right Panel: Realtime Preview -->
        <div class="hidden md:flex flex-1 bg-gray-800 p-4 md:p-8 overflow-y-auto flex-col items-center justify-start relative">
            <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                REAL-TIME PREVIEW (Kertas A4)
            </div>
            <!-- A4 Paper Container -->
            <div class="w-[794px] h-[1123px] bg-white shadow-2xl relative shrink-0 overflow-y-auto transform scale-[0.45] sm:scale-[0.55] md:scale-[0.6] xl:scale-75 origin-top mt-[-150px] sm:mt-[-100px] md:mt-0" id="pdf-preview-container">
                
                <!-- Loading Overlay -->
                <div x-show="previewLoading" class="absolute inset-0 bg-white/60 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity duration-200">
                    <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <!-- Injected HTML from Backend -->
                <div x-html="previewHtml" class="w-full min-h-[1123px] h-full relative bg-white"></div>
            </div>
        </div>
    </div>

    <!-- CSRF Token helper -->
    <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cvForm', (templateSlug, templateId, price) => ({
                templateSlug: templateSlug,
                currentStep: 0,
                loading: false,
                previewLoading: true, 
                showMobilePreview: false,
                previewHtml: '',
                newHardSkill: '', 
                newSoftSkill: '',
                newHardSkillLevel: '80',
                newLanguage: '',
                newTool: '',
                allSteps: [
                    { id: 'pribadi', title: 'Data Pribadi' },
                    { id: 'profil', title: 'Profil Singkat' },
                    { id: 'pendidikan', title: 'Pendidikan' },
                    { id: 'pengalaman', title: 'Pengalaman Kerja' },
                    { id: 'magang', title: 'Pengalaman Magang' },
                    { id: 'organisasi', title: 'Organisasi & Kepanitiaan' },
                    { id: 'volunteer', title: 'Volunteer' },
                    { id: 'project', title: 'Project / Portfolio' },
                    { id: 'skill', title: 'Skill & Tools' },
                    { id: 'sertifikat', title: 'Sertifikat & Prestasi' }
                ],
                steps: [],
                data: {
                    template_id: templateId,
                    name: '',
                    email: '',
                    phone: '',
                    address: '',
                    linkedin: '',
                    website: '',
                    social_media: '',
                    job_title: '',
                    profile: '',
                    photo: null,
                    educations: [],
                    experiences: [],
                    internships: [],
                    organizations: [],
                    volunteers: [],
                    projects: [],
                    skills: [],
                    tools: [],
                    certificates: [],
                    awards: [],
                    languages: []
                },

                init() {
                    // Determine which steps to show based on template
                    const templateConfig = {
                        'ats': ['pribadi', 'profil', 'pendidikan', 'pengalaman', 'magang', 'organisasi', 'skill', 'sertifikat', 'project', 'bahasa'],
                        'fresh-graduate': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'bahasa'],
                        'student': ['pribadi', 'profil', 'pendidikan', 'organisasi', 'magang', 'project', 'skill', 'sertifikat', 'volunteer', 'bahasa'],
                        'job-application': ['pribadi', 'profil', 'pengalaman', 'pendidikan', 'skill', 'sertifikat', 'project', 'organisasi', 'bahasa'],
                        'creative': ['pribadi', 'profil', 'skill', 'pengalaman', 'project', 'pendidikan', 'sertifikat', 'bahasa'],
                        'modern': ['pribadi', 'profil', 'pendidikan', 'pengalaman', 'magang', 'project', 'organisasi', 'skill', 'sertifikat'],
                        'elegant': ['pribadi', 'profil', 'pendidikan', 'pengalaman', 'magang', 'project', 'organisasi', 'skill', 'sertifikat']
                    };
                    
                    const activeStepIds = templateConfig[this.templateSlug] || templateConfig['ats'];
                    this.steps = this.allSteps.filter(step => activeStepIds.includes(step.id));
                    
                    // Initial render
                    this.updatePreview();
                },

                handlePhotoUpload(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.data.photo = e.target.result;
                            this.updatePreview();
                        };
                        reader.readAsDataURL(file);
                    } else {
                        this.data.photo = null;
                        this.updatePreview();
                    }
                },

                nextStep() {
                    if (this.currentStep < this.steps.length - 1) {
                        this.currentStep++;
                        document.getElementById('form-container').scrollTop = 0;
                    }
                },

                prevStep() {
                    if (this.currentStep > 0) {
                        this.currentStep--;
                        document.getElementById('form-container').scrollTop = 0;
                    }
                },

                addArrayItem(key, template) {
                    this.data[key].push({...template});
                    this.updatePreview();
                },

                removeArrayItem(key, index) {
                    this.data[key].splice(index, 1);
                    this.updatePreview();
                },

                addHardSkill() {
                    if (this.newHardSkill.trim() !== '') {
                        this.data.skills.push({ name: this.newHardSkill.trim(), level: this.newHardSkillLevel });
                        this.newHardSkill = '';
                        this.updatePreview();
                    }
                },

                addSoftSkill() {
                    if (this.newSoftSkill.trim() !== '') {
                        this.data.skills.push({ name: this.newSoftSkill.trim(), level: null });
                        this.newSoftSkill = '';
                        this.updatePreview();
                    }
                },

                addTool() {
                    if (this.newTool.trim() !== '') {
                        this.data.tools.push({ name: this.newTool.trim() });
                        this.newTool = '';
                        this.updatePreview();
                    }
                },

                async updatePreview() {
                    this.previewLoading = true;
                    try {
                        const response = await fetch(`/cv/preview/${this.templateSlug}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                'Accept': 'text/html'
                            },
                            body: JSON.stringify({
                                cv: this.data,
                                educations: this.data.educations,
                                experiences: this.data.experiences,
                                organizations: this.data.organizations,
                                skills: this.data.skills,
                                internships: this.data.internships,
                                certificates: this.data.certificates,
                                projects: this.data.projects,
                                volunteers: this.data.volunteers,
                                tools: this.data.tools
                            })
                        });
                        
                        if (response.ok) {
                            this.previewHtml = await response.text();
                        }
                    } catch (error) {
                        console.error("Preview error:", error);
                    } finally {
                        this.previewLoading = false;
                    }
                },

                async checkout() {
                    @guest
                    if(typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Akses Terbatas!',
                            text: 'Silakan Login atau Mendaftar terlebih dahulu untuk menyimpan dan mengunduh CV Anda.',
                            showCancelButton: true,
                            confirmButtonColor: '#4f46e5',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Login Sekarang',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.dispatchEvent(new CustomEvent('open-login'));
                            }
                        });
                    } else {
                        alert('Silakan login terlebih dahulu!');
                        window.dispatchEvent(new CustomEvent('open-login'));
                    }
                    return;
                    @endguest

                    if (!this.data.name || !this.data.email) {
                        alert("Mohon isi Nama Lengkap dan Email di Step 1.");
                        this.currentStep = 0;
                        return;
                    }
                    
                    this.loading = true;
                    try {
                        const response = await fetch('{{ route("cv.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.getElementById('csrf_token').value,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(this.data)
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok && result.redirect) {
                            window.location.href = result.redirect;
                        } else {
                            alert(result.message || "Gagal menyimpan CV. Periksa kembali form Anda.");
                            console.error(result);
                        }
                    } catch (error) {
                        alert("Terjadi kesalahan sistem saat menghubungi server.");
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                }
            }));
        });
    </script>

    <!-- Dedicated Mobile Preview Modal -->
    <div x-cloak x-show="showMobilePreview" class="md:hidden fixed inset-0 z-[100] bg-gray-800 overflow-y-auto overflow-x-hidden">
        <!-- Close Button -->
        <button @click="showMobilePreview = false" class="absolute top-4 right-4 bg-white/20 hover:bg-white/30 text-white rounded-full p-2 transition z-[110]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        
        <div class="text-gray-400 font-bold uppercase tracking-widest text-xs mb-4 flex items-center justify-center gap-2 mt-6">
            REAL-TIME PREVIEW
        </div>

        <!-- Dynamic Scaled Wrapper -->
        <div class="w-full flex justify-center pb-12" x-data="{ scale: 0.45, paperHeight: 1123 }" x-init="
            scale = Math.min(0.95, (window.innerWidth - 32) / 794);
            $watch('previewHtml', value => {
                setTimeout(() => {
                    if ($refs.paper) paperHeight = Math.max(1123, $refs.paper.scrollHeight);
                }, 100);
            });
        ">
            <!-- We need a spacer div to reserve the exact scaled height so the modal scrolls correctly -->
            <div :style="`width: ${794 * scale}px; height: ${paperHeight * scale}px; position: relative; transition: height 0.3s;`">
                <!-- The actual paper, absolutely positioned and scaled from top-left -->
                <div x-ref="paper" class="bg-white shadow-2xl absolute top-0 left-0" 
                     :style="`width: 794px; min-height: 1123px; transform: scale(${scale}); transform-origin: top left;`" 
                     x-html="previewHtml">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Mobile Preview Floating Button -->
    <button @click="showMobilePreview = true" class="md:hidden fixed bottom-6 right-6 bg-gray-900 text-white rounded-full p-4 shadow-2xl z-50 hover:bg-gray-800 transition flex items-center justify-center">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
    </button>

    @include('components.system-heartbeat')

</body>
</html>
