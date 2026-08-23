<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $templates = [
            [
                'name' => 'ATS Friendly Standard',
                'slug' => 'ats',
                'description' => 'Desain rapi berstandar internasional yang lolos sistem Applicant Tracking System (ATS) perusahaan multinasional.',
                'thumbnail' => '/images/cv/ats-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modern Minimalist',
                'slug' => 'modern',
                'description' => 'Tampilan bersih, modern, dan profesional dengan tata letak dua kolom yang ringkas dan memikat HRD.',
                'thumbnail' => '/images/cv/modern-preview.png',
                'price' => 5000,
                'price_normal' => 20000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Creative & Designer',
                'slug' => 'creative',
                'description' => 'Sangat cocok untuk desainer grafis, UI/UX, konten kreator, dan profesional industri kreatif.',
                'thumbnail' => '/images/cv/creative-preview.png',
                'price' => 7000,
                'price_normal' => 25000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elegant Executive',
                'slug' => 'elegant',
                'description' => 'Gaya formal dan berkelas untuk posisi manajerial, supervisor, perbankan, dan corporate executive.',
                'thumbnail' => '/images/cv/elegant-preview.png',
                'price' => 7000,
                'price_normal' => 25000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Fresh Graduate & Pemula',
                'slug' => 'fresh-graduate',
                'description' => 'Didesain khusus untuk lulusan baru yang ingin menonjolkan potensi, organisasi, dan skill terbaik.',
                'thumbnail' => '/images/cv/fresh-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lamaran Kerja Formal',
                'slug' => 'job-application',
                'description' => 'Format standar BUMN, CPNS, dan instansi pemerintahan yang mengutamakan struktur formal.',
                'thumbnail' => '/images/cv/formal-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pelajar & Magang (Internship)',
                'slug' => 'student',
                'description' => 'Template simpel dan to-the-point untuk kebutuhan melamar magang, beasiswa, atau organisasi kampus.',
                'thumbnail' => '/images/cv/student-preview.png',
                'price' => 5000,
                'price_normal' => 15000,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($templates as $template) {
            DB::table('cv_templates')->updateOrInsert(
                ['slug' => $template['slug']],
                $template
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
