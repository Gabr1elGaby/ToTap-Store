<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$preview_method = <<<'PHP'
    public function previewExample($slug)
    {
        $template = Template::where('slug', $slug)->firstOrFail();
        
        $userData = [
            'cv' => [
                'name' => 'John Doe',
                'job_title' => 'Software Engineer',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'location' => 'Jakarta, Indonesia',
                'linkedin' => 'linkedin.com/in/johndoe',
                'website' => 'johndoe.com',
                'summary' => 'Seorang Software Engineer berdedikasi dengan pengalaman lebih dari 3 tahun dalam pengembangan web menggunakan PHP, Laravel, dan JavaScript. Memiliki rekam jejak yang terbukti dalam membangun sistem yang skalabel dan efisien serta bekerja sama dalam tim lintas divisi untuk mencapai target perusahaan.',
                'photo' => '', 
                'educations' => [
                    [
                        'degree' => 'S1 Teknik Informatika',
                        'major' => 'Ilmu Komputer',
                        'university' => 'Universitas Indonesia',
                        'start_year' => '2018',
                        'end_year' => '2022'
                    ]
                ],
                'experiences' => [
                    [
                        'company' => 'PT Teknologi Nusantara',
                        'role' => 'Backend Developer',
                        'start_date' => 'Jan 2023',
                        'end_date' => 'Sekarang',
                        'description' => '- Mengembangkan dan memelihara REST API menggunakan Laravel.'."\n".'- Mengoptimalkan query database MySQL yang meningkatkan performa aplikasi hingga 40%.'
                    ],
                    [
                        'company' => 'Startup Digital Kreatif',
                        'role' => 'Web Developer',
                        'start_date' => 'Jun 2022',
                        'end_date' => 'Des 2022',
                        'description' => '- Membangun aplikasi internal perusahaan menggunakan Vue.js dan Tailwind CSS.'
                    ]
                ],
                'internships' => [
                    [
                        'company' => 'Gojek Indonesia',
                        'role' => 'Software Engineer Intern',
                        'start_date' => 'Jun 2021',
                        'end_date' => 'Agu 2021',
                        'description' => 'Berpartisipasi dalam pengembangan fitur layanan pemesanan makanan.'
                    ]
                ],
                'organizations' => [
                    [
                        'organization_name' => 'BEM Fasilkom UI',
                        'role' => 'Ketua Divisi IT',
                        'period' => '2020 - 2021',
                        'description' => 'Memimpin tim beranggotakan 10 orang untuk membangun website profil organisasi.'
                    ]
                ],
                'projects' => [
                    [
                        'name' => 'Sistem Manajemen Inventaris',
                        'technologies' => 'Laravel, MySQL, Tailwind',
                        'link' => 'github.com/johndoe/inventaris',
                        'description' => 'Aplikasi open-source untuk melacak stok barang secara real-time.'
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
                        'description' => 'Mengajarkan dasar-dasar coding menggunakan Scratch dan Python kepada anak-anak panti asuhan.'
                    ]
                ],
                'skills' => [
                    ['name' => 'PHP / Laravel', 'level' => '90'],
                    ['name' => 'JavaScript / Vue', 'level' => '85'],
                    ['name' => 'MySQL', 'level' => '80'],
                    ['name' => 'Git / GitHub', 'level' => '85'],
                    ['name' => 'Pemecahan Masalah', 'level' => null],
                    ['name' => 'Kerja Sama Tim', 'level' => null],
                    ['name' => 'Komunikasi', 'level' => null],
                ]
            ]
        ];

        return view('cv.templates.' . $template->slug, compact('userData', 'template'));
    }

    public function preview(Request $request, $slug)
PHP;

$content = str_replace('    public function preview(Request $request, $slug)', $preview_method, $content);

file_put_contents($file, $content);
echo "Added previewExample controller method.\n";
