<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

// Find the previewExample method and replace the $userData array
$regex = '/\$userData = \[\s*\'cv\' => \[\s*\'name\' => \'John Doe\'.*?\]\s*\];/s';

$mock_data = <<<PHP
\$userData = [
            'cv' => [
                'name' => 'John Doe',
                'job_title' => 'Software Engineer',
                'email' => 'johndoe@example.com',
                'phone' => '081234567890',
                'location' => 'Jakarta, Indonesia',
                'linkedin' => 'linkedin.com/in/johndoe',
                'website' => 'johndoe.com',
                'summary' => 'Seorang Software Engineer berdedikasi dengan pengalaman lebih dari 3 tahun dalam pengembangan web menggunakan PHP, Laravel, dan JavaScript. Memiliki rekam jejak yang terbukti dalam membangun sistem yang skalabel dan efisien serta bekerja sama dalam tim lintas divisi.',
                'photo' => '', 
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
PHP;

$content = preg_replace($regex, $mock_data, $content);

file_put_contents($file, $content);
echo "Fixed mock data properties.\n";
