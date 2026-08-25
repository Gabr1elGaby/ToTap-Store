<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Mahasiswa / Student</title>
    <style>
        @page { margin: 0px; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0; padding: 0;
            font-size: 10pt;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.4;
        }
        
        /* Continuous Sidebar Background across all pages */
        .sidebar-bg {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 33%;
            background-color: #831843;
            z-index: -1000;
        }
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            width: 33%;
            padding: 40px 25px;
            box-sizing: border-box;
            color: #fdf2f8;
        }
        .content {
            margin-left: 33%;
            width: 67%;
            background-color: transparent;
            padding: 40px 35px;
            box-sizing: border-box;
        }

        /* Photo Area */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        .photo {
            width: 120px; height: 120px;
            border-radius: 50%;
            border: 4px solid #fbcfe8;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        
        .name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 5px;
            color: #ffffff;
            line-height: 1.2;
        }
        .job-title {
            font-size: 9.5pt;
            color: #fbcfe8;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 25px;
        }

        /* Left Col Headers */
        .left-header {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #fbcfe8;
            padding-bottom: 4px;
            margin-bottom: 12px;
            margin-top: 20px;
            letter-spacing: 1px;
            color: #ffffff;
        }
        
        .contact-item {
            margin-bottom: 10px;
            font-size: 9pt;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-label {
            font-weight: bold;
            color: #fbcfe8;
            text-transform: uppercase;
            font-size: 7.5pt;
            display: block;
            margin-bottom: 2px;
        }
        .contact-value { color: #fdf2f8; }

        /* Right Col Elements */
        .right-header {
            font-size: 12pt;
            font-weight: bold;
            color: #831843;
            text-transform: uppercase;
            border-bottom: 2px solid #fce7f3;
            padding-bottom: 4px;
            margin-bottom: 15px;
            margin-top: 25px;
            letter-spacing: 0.5px;
        }
        .right-header:first-child { margin-top: 0; }
        
        .profile-text { text-align: justify; margin-bottom: 20px; }

        .item { margin-bottom: 15px; page-break-inside: avoid; }
        .item-title-row { width: 100%; margin-bottom: 2px; }
        .item-title { font-weight: bold; font-size: 10.5pt; color: #1e293b; }
        .item-date { text-align: right; font-size: 9pt; color: #831843; font-weight: bold; white-space: nowrap; }
        .item-subtitle { font-size: 9.5pt; color: #64748b; margin-bottom: 3px; }
        .item-desc { font-size: 9.5pt; color: #334155; text-align: justify; }

        .skill-list { list-style: none; padding: 0; margin: 0; }
        .skill-list li { margin-bottom: 6px; font-size: 9.5pt; color: #fdf2f8; }
        .skill-list li::before {
            content: "•";
            color: #fbcfe8;
            margin-right: 8px;
            font-weight: bold;
        }

    </style>
</head>
<body>
    @php
        $uData = isset($userData) ? $userData : get_defined_vars();
        $cvRaw = $uData['cv'] ?? ($cv ?? ($data ?? []));
        $data = is_object($cvRaw) ? $cvRaw : (object)$cvRaw;

        $getVal = function($item, ...$keys) {
            if (is_object($item)) {
                foreach ($keys as $k) {
                    if (isset($item->$k) && $item->$k !== '' && $item->$k !== null) return $item->$k;
                }
            } elseif (is_array($item)) {
                foreach ($keys as $k) {
                    if (isset($item[$k]) && $item[$k] !== '' && $item[$k] !== null) return $item[$k];
                }
            }
            return '';
        };

        $getCol = function($key) use ($uData) {
            if (isset($uData[$key]) && (is_array($uData[$key]) || $uData[$key] instanceof \Illuminate\Support\Collection)) {
                return collect($uData[$key])->map(fn($i) => (object)$i);
            }
            if (isset($uData['cv']) && is_array($uData['cv']) && isset($uData['cv'][$key]) && is_array($uData['cv'][$key])) {
                return collect($uData['cv'][$key])->map(fn($i) => (object)$i);
            }
            if (isset($uData['cv']) && is_object($uData['cv']) && isset($uData['cv']->$key)) {
                return collect($uData['cv']->$key)->map(fn($i) => (object)$i);
            }
            return collect([]);
        };

        $educations    = $getCol('educations');
        $experiences   = $getCol('experiences');
        $internships   = $getCol('internships');
        $organizations = $getCol('organizations');
        $projects      = $getCol('projects');
        $certificates  = $getCol('certificates');
        $skills        = $getCol('skills');

        $hard_skills = $skills->filter(fn($s) => isset($s->level) && $s->level !== '' && $s->level !== null)->all();
        $soft_skills = $skills->filter(fn($s) => !isset($s->level) || $s->level === '' || $s->level === null)->all();
    @endphp

    <!-- CONTINUOUS SIDEBAR BACKGROUND -->
    <div class="sidebar-bg"></div>

    <!-- LEFT SIDEBAR -->
    <div class="sidebar">
        @if(!empty($data->photo))
        <div class="photo-wrapper">
            <img src="{{ $data->photo }}" class="photo">
        </div>
        @endif

        <div class="name">{{ $data->name ?? 'NAMA MAHASISWA' }}</div>
        <div class="job-title">{{ $data->job_title ?? 'MAHASISWA / PELAJAR' }}</div>

        <div class="left-header">Kontak</div>
        @if(!empty($data->phone))
        <div class="contact-item">
            <span class="contact-label">Telepon / WA</span>
            <span class="contact-value">{{ $data->phone }}</span>
        </div>
        @endif
        @if(!empty($data->email))
        <div class="contact-item">
            <span class="contact-label">Email</span>
            <span class="contact-value">{{ $data->email }}</span>
        </div>
        @endif
        @if($getVal($data, 'address', 'location') !== '')
        <div class="contact-item">
            <span class="contact-label">Domisili</span>
            <span class="contact-value">{{ $getVal($data, 'address', 'location') }}</span>
        </div>
        @endif
        @if(!empty($data->linkedin))
        <div class="contact-item">
            <span class="contact-label">LinkedIn</span>
            <span class="contact-value">{{ $data->linkedin ?? '' }}</span>
        </div>
        @endif
        @if(!empty($data->website))
        <div class="contact-item">
            <span class="contact-label">Website / Portofolio</span>
            <span class="contact-value">{{ $data->website ?? '' }}</span>
        </div>
        @endif
        @if(!empty($data->social_media))
        <div class="contact-item">
            <span class="contact-label">Media Sosial</span>
            <span class="contact-value">{{ $data->social_media ?? '' }}</span>
        </div>
        @endif

        @if(count($hard_skills) > 0)
        <div class="left-header">Keahlian Teknis</div>
        <div style="margin-bottom: 15px;">
            @foreach($hard_skills as $skill)
            @php $lvl = $skill->level ?? 75; @endphp
            <div style="margin-bottom: 8px;">
                <div style="font-size: 9pt; margin-bottom: 2px; color: #fdf2f8;">{{ $skill->name ?? '' }}</div>
                <div style="width: 100%; background-color: #9d174d; height: 4px; border-radius: 2px;">
                    <div style="width: {{ $lvl }}%; background-color: #fbcfe8; height: 4px; border-radius: 2px;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if(count($soft_skills) > 0)
        <div class="left-header">Karakter Pribadi</div>
        <ul class="skill-list">
            @foreach($soft_skills as $skill)
            <li>{{ $skill->name ?? '' }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <!-- MAIN RIGHT CONTENT -->
    <div class="content">
        @if(!empty($data->profile))
        <div class="right-header">Tentang Saya</div>
        <div class="profile-text">
            {!! nl2br(e($data->profile)) !!}
        </div>
        @endif

        @if(count($educations) > 0)
        <div class="right-header">Riwayat Pendidikan</div>
        @foreach($educations as $edu)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $edu->institution ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</td>
                </tr>
            </table>
            @php
                $deg = $edu->degree ?? '';
                $maj = $getVal($edu, 'major', 'field');
            @endphp
            <div class="item-subtitle">{{ $deg }}{{ $maj !== '' ? ($deg !== '' ? ' - ' : '') . $maj : '' }}</div>
            @if(!empty($edu->description))
            <div class="item-desc">{!! nl2br(e($edu->description)) !!}</div>
            @endif
        </div>
        @endforeach
        @endif

        @if(count($organizations) > 0)
        <div class="right-header">Pengalaman Organisasi & Kepanitiaan</div>
        @foreach($organizations as $org)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $org->role ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $org->start_year ?? '' }} - {{ $org->end_year ?? '' }}</td>
                </tr>
            </table>
            <div class="item-subtitle">{{ $org->name ?? '' }}</div>
            <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
        </div>
        @endforeach
        @endif

        @if(count($internships) > 0)
        <div class="right-header">Pengalaman Magang</div>
        @foreach($internships as $int)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $int->position ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
                </tr>
            </table>
            <div class="item-subtitle">{{ $int->company ?? '' }} @if(!empty($int->location)) | {{ $int->location }} @endif</div>
            <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
        </div>
        @endforeach
        @endif

        @if(count($projects) > 0)
        <div class="right-header">Proyek Akademik & Portofolio</div>
        @foreach($projects as $proj)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $proj->name ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $proj->year ?? $proj->link ?? '' }}</td>
                </tr>
            </table>
            @php
                $projSub = array_filter([$proj->role ?? '', $proj->technologies ?? '', (!empty($proj->year) ? $proj->link ?? '' : '')]);
            @endphp
            @if(!empty($projSub))
            <div class="item-subtitle">{{ implode(' | ', $projSub) }}</div>
            @endif
            @if(!empty($proj->description))
            <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
            @endif
        </div>
        @endforeach
        @endif

        @if(count($certificates) > 0)
        <div class="right-header">Sertifikasi, Lomba & Pelatihan</div>
        @foreach($certificates as $cert)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $cert->name ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $cert->year ?? '' }}</td>
                </tr>
            </table>
            <div class="item-desc">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
        </div>
        @endforeach
        @endif
    </div>
</body>
</html>
