<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Kreatif</title>
    <style>
        @page {
            margin: 0px;
        }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9.5pt;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.45;
        }
        
        /* Continuous Sidebar Background across all pages */
        .sidebar-bg {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 34%;
            background-color: #0f172a;
            z-index: -1000;
        }
        
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            width: 34%;
            padding: 35px 22px;
            box-sizing: border-box;
            color: #cbd5e1;
        }
        
        .content {
            margin-left: 34%;
            width: 66%;
            padding: 35px 30px;
            box-sizing: border-box;
            background-color: transparent;
        }
        
        /* Photo */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 18px;
        }
        .photo {
            width: 110px; height: 110px;
            border-radius: 50%;
            border: 3px solid #38bdf8;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        /* Identity */
        .name {
            font-size: 15pt;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            line-height: 1.2;
        }
        .job-title {
            font-size: 9pt;
            color: #38bdf8;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 20px 0;
            font-weight: bold;
        }

        /* Left Section */
        .left-header {
            font-size: 10pt;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #38bdf8;
            padding-bottom: 3px;
            margin-bottom: 12px;
            margin-top: 20px;
        }
        .left-header:first-of-type {
            margin-top: 0;
        }
        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .contact-list li {
            margin-bottom: 9px;
            font-size: 8.5pt;
            word-wrap: break-word;
            word-break: break-all;
            line-height: 1.35;
        }
        .contact-label {
            color: #38bdf8;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1px;
        }
        .contact-value {
            color: #f8fafc;
        }
        
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            margin-bottom: 5px;
            font-size: 8.5pt;
            color: #f1f5f9;
        }
        .skill-list li::before {
            content: "•";
            color: #38bdf8;
            margin-right: 6px;
            font-weight: bold;
        }

        /* Right Section */
        .right-header {
            font-size: 11pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 3px;
            margin-bottom: 12px;
            margin-top: 20px;
        }
        .right-header:first-of-type {
            margin-top: 0;
        }
        .profile-text {
            font-size: 9.5pt;
            text-align: justify;
            margin-bottom: 16px;
            line-height: 1.45;
        }

        /* Timeline Items */
        .item {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #0f172a;
        }
        .item-date {
            font-size: 8.5pt;
            color: #0284c7;
            font-weight: bold;
            text-align: right;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 9.5pt;
            color: #475569;
            font-weight: 600;
            margin-bottom: 3px;
        }
        .item-desc {
            font-size: 9pt;
            color: #334155;
            text-align: justify;
            line-height: 1.4;
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
        $tools         = $getCol('tools');

        $hard_skills = $skills->filter(fn($s) => isset($s->level) && $s->level !== '' && $s->level !== null)->all();
        $soft_skills = $skills->filter(fn($s) => !isset($s->level) || $s->level === '' || $s->level === null)->all();
    @endphp

    <!-- CONTINUOUS SIDEBAR BACKGROUND -->
    <div class="sidebar-bg"></div>

    <!-- LEFT SIDEBAR CONTENT -->
    <div class="sidebar">
        @if(!empty($data->photo))
        <div class="photo-wrapper">
            <img src="{{ $data->photo }}" class="photo">
        </div>
        @endif

        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="job-title">{{ $data->job_title ?? 'PROFESI / ROLE' }}</div>

        <div class="left-header">Kontak Pribadi</div>
        <ul class="contact-list">
            @if(!empty($data->phone))
            <li><span class="contact-label">Telepon / WA</span> <span class="contact-value">{{ $data->phone }}</span></li>
            @endif
            @if(!empty($data->email))
            <li><span class="contact-label">Email</span> <span class="contact-value">{{ $data->email }}</span></li>
            @endif
            @if($getVal($data, 'address', 'location') !== '')
            <li><span class="contact-label">Domisili</span> <span class="contact-value">{{ $getVal($data, 'address', 'location') }}</span></li>
            @endif
            @if(!empty($data->linkedin))
            <li><span class="contact-label">LinkedIn</span> <span class="contact-value">{{ $data->linkedin ?? '' }}</span></li>
            @endif
            @if(!empty($data->website))
            <li><span class="contact-label">Website / Portofolio</span> <span class="contact-value">{{ $data->website ?? '' }}</span></li>
            @endif
            @if(!empty($data->social_media))
            <li><span class="contact-label">Social Media</span> <span class="contact-value">{{ $data->social_media ?? '' }}</span></li>
            @endif
        </ul>

        @if(count($hard_skills) > 0)
        <div class="left-header">Keahlian Teknis</div>
        <div style="margin-bottom: 16px;">
            @foreach($hard_skills as $skill)
            <div style="margin-bottom: 6px;">
                <div style="font-size: 8.5pt; margin-bottom: 2px; color: #fff;">{{ $skill->name ?? '' }} <span style="float: right; color: #38bdf8; font-weight: bold;">{{ $skill->level ?? '' }}%</span></div>
                <div style="width: 100%; background-color: #334155; height: 4px; border-radius: 2px;">
                    <div style="width: {{ $skill->level ?? '' }}%; background-color: #38bdf8; height: 4px; border-radius: 2px;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
        
        @if(count($soft_skills) > 0)
        <div class="left-header">Keahlian Interpersonal</div>
        <ul class="skill-list" style="margin-bottom: 16px;">
            @foreach($soft_skills as $skill)
            <li>{{ $skill->name ?? '' }}</li>
            @endforeach
        </ul>
        @endif

        @if(count($tools) > 0)
        <div class="left-header">Perangkat Lunak & Tools</div>
        <ul class="skill-list">
            @foreach($tools as $tool)
            <li>{{ $tool->name ?? '' }}</li>
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

        @if(count($experiences) > 0)
        <div class="right-header">Pengalaman Kerja</div>
        @foreach($experiences as $exp)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $exp->position ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $exp->start_year ?? '' }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_year }}</td>
                </tr>
            </table>
            <div class="item-subtitle">{{ $exp->company ?? '' }} @if(!empty($exp->location)) | {{ $exp->location ?? '' }} @endif</div>
            <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
        </div>
        @endforeach
        @endif

        @if(count($projects) > 0)
        <div class="right-header">Proyek & Portofolio</div>
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

        @if(count($certificates) > 0)
        <div class="right-header">Sertifikasi & Pelatihan</div>
        @foreach($certificates as $cert)
        <div class="item">
            <table class="item-title-row" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%;">{{ $cert->name ?? '' }}</td>
                    <td class="item-date" style="width: 25%;">{{ $cert->year ?? '' }}</td>
                </tr>
            </table>
            <div class="item-subtitle">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
        </div>
        @endforeach
        @endif
    </div>
</body>
</html>
