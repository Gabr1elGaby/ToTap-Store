<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CvController extends Controller
{
    public function index()
    {
        $templates = DB::table('cv_templates')->where('status', 'active')->get();
        return view('cv.index', compact('templates'));
    }

    public function create(Request $request)
    {
        $templateSlug = $request->query('template', 'ats');
        $template = DB::table('cv_templates')->where('slug', $templateSlug)->first();
        if (!$template) abort(404, 'Template not found');

        return view('cv.create', compact('template'));
    }

    public function store(Request $request)
    {
        // Accept the incoming JSON payload and save to tables
        $data = $request->validate([
            'template_id' => 'required|exists:cv_templates,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'linkedin' => 'nullable|string',
            'website' => 'nullable|string',
            'job_title' => 'nullable|string',
            'profile' => 'nullable|string',
            'photo' => 'nullable|string',
            'social_media' => 'nullable|string',
            
            'educations' => 'nullable|array',
            'experiences' => 'nullable|array',
            'organizations' => 'nullable|array',
            'internships' => 'nullable|array',
            'skills' => 'nullable|array',
            'tools' => 'nullable|array',
            'certificates' => 'nullable|array',
            'projects' => 'nullable|array',
            
            'volunteers' => 'nullable|array',
        ]);

        DB::beginTransaction();
        try {
            $photoPath = null;
            if (!empty($data['photo'])) {
                if (preg_match('/^data:image\/(\w+);base64,/', $data['photo'], $type)) {
                    $photoData = substr($data['photo'], strpos($data['photo'], ',') + 1);
                    $type = strtolower($type[1]); // jpg, png, gif
                    
                    if (in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                        $photoData = base64_decode($photoData);
                        if ($photoData !== false) {
                            $filename = uniqid('cv_photo_') . '.' . $type;
                            file_put_contents(public_path('images/' . $filename), $photoData);
                            $photoPath = 'images/' . $filename;
                        }
                    }
                }
            }

            $accessToken = 'cv_' . \Illuminate\Support\Str::random(24);
            $invoiceNumber = \App\Helpers\InvoiceHelper::generateCvInvoice();

            $cvId = DB::table('cvs')->insertGetId([
                'access_token' => $accessToken,
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'template_id' => $data['template_id'],
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'address' => $data['address'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'website' => (isset($data['website']) ? $data['website'] . ' ' : '') . ($data['social_media'] ?? ''),
                'photo' => $photoPath,
                'job_title' => $data['job_title'] ?? null,
                'profile' => $data['profile'] ?? null,
                'status' => 'PENDING',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($data['educations'])) {
                foreach ($data['educations'] as $edu) {
                    DB::table('cv_educations')->insert([
                        'cv_id' => $cvId,
                        'institution' => $edu['institution'] ?? '',
                        'major' => $edu['major'] ?? null,
                        'degree' => $edu['degree'] ?? null,
                        'start_year' => $edu['start_year'] ?? null,
                        'end_year' => $edu['end_year'] ?? null,
                        'description' => $edu['description'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            if (!empty($data['experiences'])) {
                foreach ($data['experiences'] as $exp) {
                    DB::table('cv_experiences')->insert([
                        'cv_id' => $cvId,
                        'company' => $exp['company'] ?? '',
                        'position' => $exp['position'] ?? '',
                        'location' => $exp['location'] ?? null,
                        'start_year' => $exp['start_year'] ?? null,
                        'end_year' => $exp['end_year'] ?? null,
                        'is_current' => $exp['is_current'] ?? false,
                        'description' => $exp['description'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            $orgs = array_merge($request->input('organizations', []), $request->input('volunteers', []));
            if (!empty($orgs)) {
                foreach ($orgs as $org) {
                    DB::table('cv_organizations')->insert([
                        'cv_id' => $cvId,
                        'organization_name' => $org['organization_name'] ?? $org['name'] ?? '',
                        'role' => $org['role'] ?? '',
                        'period' => ($org['period'] ?? '') . ($org['year'] ?? ''),
                        'description' => $org['description'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            if (!empty($data['internships'])) {
                foreach ($data['internships'] as $int) {
                    DB::table('cv_internships')->insert([
                        'cv_id' => $cvId,
                        'company' => $int['company'] ?? '',
                        'position' => $int['position'] ?? '',
                        'period' => ($int['start_year'] ?? '') . ' - ' . ($int['end_year'] ?? ''),
                        'description' => $int['description'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            $skills = array_merge($request->input('skills', []), $request->input('tools', []));
            if (!empty($skills)) {
                foreach ($skills as $skill) {
                    DB::table('cv_skills')->insert([
                        'cv_id' => $cvId,
                        'name' => $skill['name'] ?? '',
                        'level' => $skill['level'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            if (!empty($data['certificates'])) {
                foreach ($data['certificates'] as $cert) {
                    DB::table('cv_certificates')->insert([
                        'cv_id' => $cvId,
                        'name' => $cert['name'] ?? '',
                        'publisher' => $cert['issuer'] ?? null,
                        'year' => $cert['year'] ?? null,
                        'link' => null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }

            if (!empty($data['projects'])) {
                foreach ($data['projects'] as $proj) {
                    DB::table('cv_projects')->insert([
                        'cv_id' => $cvId,
                        'name' => $proj['name'] ?? '',
                        'description' => $proj['description'] ?? null,
                        'technologies' => $proj['role'] ?? null,
                        'link' => $proj['year'] ?? null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }



            DB::commit();

            return response()->json(['redirect' => route('cv.checkout.show', $accessToken)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save CV', 'error' => $e->getMessage()], 500);
        }
    }

    public function download($token)
    {
        $cv = DB::table('cvs')->where('access_token', $token)->orWhere('id', $token)->first();
        if (!$cv) abort(404, 'CV not found');

        $isAdmin = auth()->check() && (auth()->user()->role === 'admin' || !empty(auth()->user()->is_admin));
        if ($cv->user_id && auth()->check() && auth()->id() !== $cv->user_id && !$isAdmin && $token == $cv->id) {
            abort(403, 'Akses tidak diizinkan untuk CV ini.');
        }

        $cvId = $cv->id;
        $template = DB::table('cv_templates')->where('id', $cv->template_id)->first();
        
        $educations = DB::table('cv_educations')->where('cv_id', $cvId)->get();
        $experiences = DB::table('cv_experiences')->where('cv_id', $cvId)->get();
        $organizations = DB::table('cv_organizations')->where('cv_id', $cvId)->get();
        $internships = DB::table('cv_internships')->where('cv_id', $cvId)->get();
        $skills = DB::table('cv_skills')->where('cv_id', $cvId)->get();
        $certificates = DB::table('cv_certificates')->where('cv_id', $cvId)->get();
        $projects = DB::table('cv_projects')->where('cv_id', $cvId)->get();
        

        if (!empty($cv->photo)) {
            $cv->photo = public_path($cv->photo);
        }

        $userData = [
            'cv' => $cv,
            'educations' => $educations,
            'experiences' => $experiences,
            'organizations' => $organizations,
            'internships' => $internships,
            'skills' => $skills,
            'certificates' => $certificates,
            'projects' => $projects,
            
        ];

        if ($cv->status !== 'PAID') {
            return view('cv.pending', compact('cv', 'userData', 'template'));
        }

        $pdf = Pdf::loadView('cv.templates.' . $template->slug, array_merge($userData, [
            'userData' => $userData,
            'template' => $template,
            'data' => $cv,
        ]));
        $pdf->setPaper('a4', 'portrait');
        
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="CV-' . str_replace(' ', '_', $cv->name) . '.pdf"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);
    }

    public function previewExample($slug)
    {
        $template = DB::table('cv_templates')->where('slug', $slug)->first(); 
        if (!$template) abort(404);

        $isEnglish = ($template->language ?? '') === 'en' || str_starts_with($template->slug, 'en-');

        if ($isEnglish) {
            $userData = [
                'cv' => [
                    'name' => 'ALEXANDER WRIGHT',
                    'job_title' => 'Senior Full Stack Software Engineer',
                    'email' => 'alex.wright@example.com',
                    'phone' => '+62 812-3456-7890',
                    'address' => 'Jakarta, Indonesia (Open to Remote)',
                    'location' => 'Jakarta, Indonesia',
                    'linkedin' => 'linkedin.com/in/alexander-wright',
                    'website' => 'alexwright.dev',
                    'profile' => 'Results-driven Senior Software Engineer with 5+ years of experience architecting high-concurrency web systems and distributed microservices. Proven track record of scaling platforms to 2M+ active users and reducing API latency by 35%. Passionate about clean code, automated CI/CD pipelines, and cloud-native solutions.',
                    'summary' => 'Results-driven Senior Software Engineer with 5+ years of experience architecting high-concurrency web systems and distributed microservices. Proven track record of scaling platforms to 2M+ active users and reducing API latency by 35%. Passionate about clean code, automated CI/CD pipelines, and cloud-native solutions.',
                    'photo' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80',
                ],
                'educations' => [
                    [
                        'institution' => 'University of Indonesia',
                        'degree' => 'Bachelor of Science',
                        'major' => 'Computer Science',
                        'field' => 'Computer Science',
                        'location' => 'Jakarta, Indonesia',
                        'start_year' => '2016',
                        'end_year' => '2020',
                        'is_current' => false,
                        'description' => 'Graduated with Honors (GPA: 3.88/4.00) • Head of Competitive Programming Club'
                    ]
                ],
                'experiences' => [
                    [
                        'company' => 'Fintech Global Nexus Inc.',
                        'position' => 'Senior Backend Engineer',
                        'location' => 'Singapore / Remote',
                        'start_year' => '2022',
                        'end_year' => '',
                        'is_current' => true,
                        'description' => "• Architected and deployed payment orchestration microservices processing \$15M+ monthly volume with 99.99% uptime.\n• Spearheaded database partitioning and Redis distributed caching, slashing p99 latency from 450ms to 95ms.\n• Mentored 6 junior/mid-level engineers and established strict unit test coverage benchmarks exceeding 85%."
                    ],
                    [
                        'company' => 'Nexora Cloud Technologies',
                        'position' => 'Full Stack Software Engineer',
                        'location' => 'Jakarta, Indonesia',
                        'start_year' => '2020',
                        'end_year' => '2022',
                        'is_current' => false,
                        'description' => "• Engineered scalable RESTful and GraphQL APIs using Laravel, Node.js, and PostgreSQL.\n• Built responsive frontend customer dashboards using Vue.js and Tailwind CSS, increasing user retention by 24%.\n• Automated multi-environment Docker CI/CD deployments on AWS ECS and GitHub Actions."
                    ]
                ],
                'internships' => [],
                'organizations' => [],
                'projects' => [
                    [
                        'name' => 'CloudSentinel API Security Gateway',
                        'technologies' => 'Go, Laravel, Redis, Docker, AWS',
                        'link' => 'github.com/alexwright/cloudsentinel',
                        'description' => 'Open-source rate-limiting and JWT security proxy utilized by 1,400+ production services globally.'
                    ],
                    [
                        'name' => 'Real-Time Stock Portfolio Tracker',
                        'technologies' => 'TypeScript, React, WebSocket, PostgreSQL',
                        'link' => 'demo.alexwright.dev/tracker',
                        'description' => 'Sub-second real-time market data analytics platform with automated financial alert triggers.'
                    ]
                ],
                'certificates' => [
                    [
                        'name' => 'AWS Certified Solutions Architect – Associate',
                        'issuer' => 'Amazon Web Services (AWS)',
                        'year' => '2023'
                    ],
                    [
                        'name' => 'CKA: Certified Kubernetes Administrator',
                        'issuer' => 'The Linux Foundation & CNCF',
                        'year' => '2022'
                    ]
                ],
                'skills' => [
                    ['name' => 'PHP / Laravel Framework', 'level' => 90],
                    ['name' => 'TypeScript / Node.js', 'level' => 85],
                    ['name' => 'PostgreSQL & MySQL Database', 'level' => 80],
                    ['name' => 'RESTful & GraphQL APIs', 'level' => 85],
                    ['name' => 'Docker & Cloud Deployment', 'level' => 80],
                    ['name' => 'AWS Cloud Architecture', 'level' => 85],
                    ['name' => 'CI/CD & DevOps Automation', 'level' => 80],
                ],
                'tools' => [
                    ['name' => 'Docker & Kubernetes'],
                    ['name' => 'Git & GitHub Actions'],
                    ['name' => 'AWS & Cloudflare'],
                    ['name' => 'Postman & Jira'],
                ]
            ];
        } else {
            // INDONESIAN SAMPLE DATA (Standar Nasional & BUMN)
            $userData = [
                'cv' => [
                    'name' => 'RADITYA PRATAMA, S.Kom.',
                    'job_title' => 'Software Engineer / Full Stack Developer',
                    'email' => 'raditya.pratama@email.com',
                    'phone' => '0812-3456-7890',
                    'address' => 'Jakarta Selatan, DKI Jakarta',
                    'location' => 'Jakarta Selatan, DKI Jakarta',
                    'linkedin' => 'linkedin.com/in/radityapratama',
                    'website' => 'radityapratama.id',
                    'profile' => 'Profesional Software Engineer dengan pengalaman lebih dari 4 tahun dalam merancang dan mengembangkan aplikasi web skala besar. Berpengalaman dalam pengembangan RESTful API, arsitektur database, dan integrasi sistem pembayaran. Memiliki rekam jejak yang terbukti dalam membangun sistem yang efisien dan siap berkontribusi optimal bagi kemajuan perusahaan.',
                    'summary' => 'Profesional Software Engineer dengan pengalaman lebih dari 4 tahun dalam merancang dan mengembangkan aplikasi web skala besar. Berpengalaman dalam pengembangan RESTful API, arsitektur database, dan integrasi sistem pembayaran. Memiliki rekam jejak yang terbukti dalam membangun sistem yang efisien dan siap berkontribusi optimal bagi kemajuan perusahaan.',
                    'photo' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&auto=format&fit=crop&q=80',
                ],
                'educations' => [
                    [
                        'institution' => 'Universitas Indonesia',
                        'degree' => 'Sarjana Komputer (S.Kom.)',
                        'major' => 'Teknik Informatika / Ilmu Komputer',
                        'field' => 'Ilmu Komputer',
                        'location' => 'Depok, Jawa Barat',
                        'start_year' => '2016',
                        'end_year' => '2020',
                        'is_current' => false,
                        'description' => 'Lulus dengan predikat Sangat Memuaskan (IPK 3.84 / 4.00) • Penerima Beasiswa Prestasi Akademik'
                    ]
                ],
                'experiences' => [
                    [
                        'company' => 'PT Teknologi Nusantara Jaya',
                        'position' => 'Senior Backend Developer',
                        'location' => 'Jakarta Selatan',
                        'start_year' => '2022',
                        'end_year' => '',
                        'is_current' => true,
                        'description' => "• Mengembangkan dan mengelola arsitektur backend transaksi yang memproses puluhan ribu transaksi harian.\n• Mengoptimalkan performa kueri database MySQL dan Redis cache sehingga meningkatkan kecepatan respon server sebesar 35%.\n• Berkolaborasi aktif dengan tim frontend, mobile, dan QA dalam penerapan standar Clean Architecture."
                    ],
                    [
                        'company' => 'PT Solusi Digital Kreatif',
                        'position' => 'Web Developer',
                        'location' => 'Jakarta Barat',
                        'start_year' => '2020',
                        'end_year' => '2022',
                        'is_current' => false,
                        'description' => "• Membangun sistem informasi manajemen internal berbasis web menggunakan framework Laravel dan MySQL.\n• Mengintegrasikan payment gateway otomatis (QRIS, Virtual Account) dan webhook notifikasi transaksi."
                    ]
                ],
                'internships' => [],
                'organizations' => [],
                'projects' => [
                    [
                        'name' => 'Sistem Informasi Manajemen Kasir & Inventaris',
                        'technologies' => 'Laravel, MySQL, Tailwind CSS, Alpine.js',
                        'link' => 'github.com/raditya/pos-enterprise',
                        'description' => 'Aplikasi POS multi-cabang dengan fitur laporan keuangan real-time dan manajemen stok otomatis.'
                    ]
                ],
                'certificates' => [
                    [
                        'name' => 'Sertifikasi BNSP - Rekayasa Perangkat Lunak (Software Developer)',
                        'issuer' => 'Badan Nasional Sertifikasi Profesi (BNSP)',
                        'year' => '2023'
                    ],
                    [
                        'name' => 'AWS Certified Cloud Practitioner',
                        'issuer' => 'Amazon Web Services',
                        'year' => '2022'
                    ]
                ],
                'volunteers' => [
                    [
                        'name' => 'Relawan Pengajar Literasi Digital Nusantara',
                        'role' => 'Instruktur Pemrograman Dasar',
                        'year' => '2021',
                        'description' => 'Memberikan pelatihan dasar coding dan literasi internet sehat kepada generasi muda.'
                    ]
                ],
                'skills' => in_array($slug, ['creative', 'student', 'en-creative-designer']) ? [
                    ['name' => 'HTML5, CSS3 & Tailwind CSS', 'level' => 90],
                    ['name' => 'JavaScript & Vue.js', 'level' => 85],
                    ['name' => 'PHP & Laravel Framework', 'level' => 80],
                    ['name' => 'UI/UX & Figma Design', 'level' => 90],
                    ['name' => 'Visual Branding & Typography', 'level' => 85],
                    ['name' => 'Creative Problem Solving', 'level' => 80],
                ] : [
                    ['name' => 'PHP & Laravel Framework', 'level' => 90],
                    ['name' => 'JavaScript, Vue.js & Tailwind CSS', 'level' => 85],
                    ['name' => 'MySQL & Database Optimization', 'level' => 80],
                    ['name' => 'RESTful API & Payment Gateway', 'level' => 85],
                    ['name' => 'Git & GitHub Version Control', 'level' => 80],
                    ['name' => 'Problem Solving & Critical Thinking', 'level' => 85],
                ],
                'tools' => [
                    ['name' => 'Figma'],
                    ['name' => 'Adobe Illustrator'],
                    ['name' => 'Photoshop'],
                    ['name' => 'VS Code'],
                ]
            ];
        }

        $userData['cv']['educations'] = $userData['educations'];
        $userData['cv']['experiences'] = $userData['experiences'];
        $userData['cv']['internships'] = $userData['internships'];
        $userData['cv']['organizations'] = $userData['organizations'];
        $userData['cv']['projects'] = $userData['projects'];
        $userData['cv']['certificates'] = $userData['certificates'];
        $userData['cv']['skills'] = $userData['skills'];
        $userData['cv']['tools'] = $userData['tools'] ?? [];
        $userData['cv']['volunteers'] = $userData['volunteers'] ?? [];

        $data = (object)$userData['cv'];
        $skills = $userData['skills'];
        $tools = $userData['tools'] ?? [];
        $certificates = $userData['certificates'];
        $educations = $userData['educations'];
        $experiences = $userData['experiences'];
        $projects = $userData['projects'];
        $internships = $userData['internships'];
        $organizations = $userData['organizations'];

        return view('cv.templates.' . $template->slug, compact(
            'userData', 'template', 'data',
            'skills', 'tools', 'certificates', 'educations', 'experiences', 'projects', 'internships', 'organizations'
        ));
    }

    public function preview(Request $request, $slug)
    {
        $userData = $request->all();
        $data = (object)($userData['cv'] ?? $userData);
        $skills = $userData['skills'] ?? [];
        $tools = $userData['tools'] ?? [];
        $certificates = $userData['certificates'] ?? [];
        $educations = $userData['educations'] ?? [];
        $experiences = $userData['experiences'] ?? [];
        $projects = $userData['projects'] ?? [];
        $internships = $userData['internships'] ?? [];
        $organizations = $userData['organizations'] ?? [];

        return view('cv.templates.' . $slug, compact(
            'userData', 'data',
            'skills', 'tools', 'certificates', 'educations', 'experiences', 'projects', 'internships', 'organizations'
        ))->render();
    }
}
