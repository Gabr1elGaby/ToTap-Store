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

            $cvId = DB::table('cvs')->insertGetId([
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

            return response()->json(['redirect' => route('cv.checkout.show', $cvId)]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to save CV', 'error' => $e->getMessage()], 500);
        }
    }

    public function download($cvId)
    {
        $cv = DB::table('cvs')->where('id', $cvId)->first();
        if (!$cv) abort(404, 'CV not found');

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

        $pdf = Pdf::loadView('cv.templates.' . $template->slug, $userData);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf->download('CV-' . $cv->name . '.pdf');
    }

    public function previewExample($slug)
    {
        $template = $template = DB::table('cv_templates')->where('slug', $slug)->first(); if (!$template) abort(404);
        
        $userData = [
            'cv' => [
                'name' => 'John Doe',
                'job_title' => 'Software Engineer',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'location' => 'Jakarta, Indonesia',
                'linkedin' => 'linkedin.com/in/johndoe',
                'website' => 'johndoe.com',
                'summary' => 'Seorang Software Engineer berdedikasi dengan pengalaman lebih dari 3 tahun dalam pengembangan web menggunakan PHP, Laravel, dan JavaScript. Memiliki rekam jejak yang terbukti dalam membangun sistem yang skalabel dan efisien serta bekerja sama dalam tim lintas divisi.',
                'photo' => 'https://i.pravatar.cc/300?img=11', 
                'educations' => [
                    [
                        'institution' => 'Universitas Indonesia',
                        'degree' => 'S1 Teknik Informatika',
                        'field' => 'Ilmu Komputer',
                        'location' => 'Depok, Jawa Barat',
                        'start_year' => '2018',
                        'end_year' => '2022',
                        'is_current' => false,
                        'description' => 'Lulus dengan predikat Cum Laude (IPK 3.85)'
                    ]
                ],
                'experiences' => [
                    [
                        'company' => 'PT Teknologi Nusantara',
                        'position' => 'Backend Developer',
                        'location' => 'Jakarta',
                        'start_year' => '2023',
                        'end_year' => '',
                        'is_current' => true,
                        'description' => 'Mengembangkan dan memelihara REST API menggunakan Laravel.'
                    ],
                    [
                        'company' => 'Startup Digital Kreatif',
                        'position' => 'Web Developer',
                        'location' => 'Jakarta',
                        'start_year' => '2022',
                        'end_year' => '2023',
                        'is_current' => false,
                        'description' => 'Membangun aplikasi internal perusahaan.'
                    ]
                ],
                'internships' => [
                    [
                        'company' => 'Gojek Indonesia',
                        'position' => 'Software Engineer Intern',
                        'location' => 'Jakarta',
                        'start_year' => '2021',
                        'end_year' => '2021',
                        'is_current' => false,
                        'description' => 'Berpartisipasi dalam pengembangan fitur layanan.'
                    ]
                ],
                'organizations' => [
                    [
                        'organization_name' => 'BEM Fasilkom UI',
                        'role' => 'Ketua Divisi IT',
                        'period' => '2020 - 2021',
                        'description' => 'Memimpin tim beranggotakan 10 orang.'
                    ]
                ],
                'projects' => [
                    [
                        'name' => 'Sistem Manajemen Inventaris',
                        'technologies' => 'Laravel, MySQL, Tailwind',
                        'link' => 'github.com/johndoe/inventaris',
                        'description' => 'Aplikasi open-source untuk melacak stok barang.'
                    ]
                ],
                'certificates' => [
                    [
                        'name' => 'AWS Certified Cloud Practitioner',
                        'issuer' => 'Amazon Web Services',
                        'year' => '2023'
                    ]
                ],
                'volunteers' => [
                    [
                        'name' => 'Relawan IT Mengajar',
                        'role' => 'Mentor Pemrograman',
                        'year' => '2021',
                        'description' => 'Mengajarkan dasar-dasar coding.'
                    ]
                ],
                'skills' => [
                    ['name' => 'PHP', 'level' => 'Hard Skill'],
                    ['name' => 'Laravel', 'level' => 'Hard Skill'],
                    ['name' => 'JavaScript', 'level' => 'Hard Skill'],
                    ['name' => 'MySQL', 'level' => 'Hard Skill'],
                    ['name' => 'Kerja Sama Tim', 'level' => 'Soft Skill'],
                    ['name' => 'Komunikasi', 'level' => 'Soft Skill'],
                ]
            ]
        ];

        $data = isset($userData['cv']) ? (object)$userData['cv'] : (object)[];
        return view('cv.templates.' . $template->slug, compact('userData', 'template', 'data'));
    }

    public function preview(Request $request, $slug)
    {
        $userData = $request->all();
        // Return only the inner body of the template, no full html head for the preview panel
        return view('cv.templates.' . $slug, compact('userData'))->render();
    }
}
