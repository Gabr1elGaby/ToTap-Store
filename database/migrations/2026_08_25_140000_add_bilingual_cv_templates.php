<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('cv_templates', 'language')) {
            Schema::table('cv_templates', function (Blueprint $table) {
                $table->string('language', 10)->default('id')->after('slug');
            });
        }
        if (!Schema::hasColumn('cv_templates', 'category')) {
            Schema::table('cv_templates', function (Blueprint $table) {
                $table->string('category', 50)->default('general')->after('language');
            });
        }

        $allTemplates = [
            // ==========================================
            // 🇮🇩 TEMPLATE CV BAHASA INDONESIA
            // ==========================================
            [
                'name' => 'ATS Standar BUMN & Nasional',
                'slug' => 'ats',
                'language' => 'id',
                'category' => 'ats',
                'description' => 'Format hitam-putih rapi standar ATS Indonesia yang lolos seleksi BUMN, instansi, dan korporat nasional.',
                'thumbnail' => '/images/cv/ats-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
            ],
            [
                'name' => 'Modern Minimalis Indonesia',
                'slug' => 'modern',
                'language' => 'id',
                'category' => 'modern',
                'description' => 'Tata letak 2 kolom elegan dan rapi dengan foto formal, cocok untuk berbagai industri di Indonesia.',
                'thumbnail' => '/images/cv/modern-preview.png',
                'price' => 5000,
                'price_normal' => 20000,
                'status' => 'active',
            ],
            [
                'name' => 'Kreatif & Desain Visual Indonesia',
                'slug' => 'creative',
                'language' => 'id',
                'category' => 'creative',
                'description' => 'Desain visual berkelas untuk desainer grafis, UI/UX, agensi kreatif, media, dan konten kreator.',
                'thumbnail' => '/images/cv/creative-preview.png',
                'price' => 7000,
                'price_normal' => 25000,
                'status' => 'active',
            ],
            [
                'name' => 'Eksekutif & Manajerial Indonesia',
                'slug' => 'elegant',
                'language' => 'id',
                'category' => 'professional',
                'description' => 'Gaya formal berwibawa untuk posisi Supervisor, Manager, Perbankan, dan Profesional Senior.',
                'thumbnail' => '/images/cv/elegant-preview.png',
                'price' => 7000,
                'price_normal' => 25000,
                'status' => 'active',
            ],
            [
                'name' => 'Fresh Graduate & Pemula',
                'slug' => 'fresh-graduate',
                'language' => 'id',
                'category' => 'fresh_graduate',
                'description' => 'Menonjolkan riwayat pendidikan, IPK, organisasi kampus, kepanitiaan, dan keahlian potensial.',
                'thumbnail' => '/images/cv/fresh-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
            ],
            [
                'name' => 'Lamaran Kerja Formal CPNS & BUMN',
                'slug' => 'job-application',
                'language' => 'id',
                'category' => 'formal',
                'description' => 'Format resmi baku yang sesuai dengan persyaratan administrasi CPNS, BUMN, dan lembaga pemerintahan.',
                'thumbnail' => '/images/cv/formal-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
            ],
            [
                'name' => 'Pelajar & Magang (Internship)',
                'slug' => 'student',
                'language' => 'id',
                'category' => 'student',
                'description' => 'Template to-the-point untuk melamar magang, program beasiswa, atau kegiatan kemahasiswaan.',
                'thumbnail' => '/images/cv/student-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
            ],

            // ==========================================
            // 🇬🇧 TEMPLATE CV / RESUME BAHASA INGGRIS
            // ==========================================
            [
                'name' => 'Global Tech & Corporate ATS Resume',
                'slug' => 'en-ats-global',
                'language' => 'en',
                'category' => 'ats',
                'description' => 'International single-column ATS resume strictly compliant with US/UK recruiters and Fortune 500 ATS scanners.',
                'thumbnail' => '/images/cv/en-ats-preview.png',
                'price' => 5000,
                'price_normal' => 20000,
                'status' => 'active',
            ],
            [
                'name' => 'Silicon Valley Modern Tech',
                'slug' => 'en-modern-silicon',
                'language' => 'en',
                'category' => 'modern',
                'description' => 'High-impact 2-column tech resume highlighting quantifiable achievements, tech stacks, and live GitHub projects.',
                'thumbnail' => '/images/cv/en-modern-preview.png',
                'price' => 5000,
                'price_normal' => 25000,
                'status' => 'active',
            ],
            [
                'name' => 'International Creative & UX/UI',
                'slug' => 'en-creative-designer',
                'language' => 'en',
                'category' => 'creative',
                'description' => 'Contemporary portfolio-driven layout for Product Designers, UX Researchers, Art Directors, and Creative Leads.',
                'thumbnail' => '/images/cv/en-creative-preview.png',
                'price' => 7000,
                'price_normal' => 30000,
                'status' => 'active',
            ],
            [
                'name' => 'Global Executive & Leadership CV',
                'slug' => 'en-executive-leadership',
                'language' => 'en',
                'category' => 'professional',
                'description' => 'Distinguished multi-page executive format for Directors, VPs, Consultants, and Senior Business Leaders.',
                'thumbnail' => '/images/cv/en-executive-preview.png',
                'price' => 7000,
                'price_normal' => 30000,
                'status' => 'active',
            ],
            [
                'name' => 'Academic, Research & Fellowship CV',
                'slug' => 'en-academic-research',
                'language' => 'en',
                'category' => 'academic',
                'description' => 'Structured academic CV format for international graduate admissions, research fellowships, and grant applications.',
                'thumbnail' => '/images/cv/en-academic-preview.png',
                'price' => 5000,
                'price_normal' => 20000,
                'status' => 'active',
            ],
            [
                'name' => 'International Graduate & Internship',
                'slug' => 'en-entry-internship',
                'language' => 'en',
                'category' => 'fresh_graduate',
                'description' => 'Tailored for university graduates and students applying to global remote internships and international companies.',
                'thumbnail' => '/images/cv/en-entry-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
            ],
        ];

        foreach ($allTemplates as $template) {
            DB::table('cv_templates')->updateOrInsert(
                ['slug' => $template['slug']],
                array_merge($template, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    public function down(): void
    {
        // Keep templates intact
    }
};
