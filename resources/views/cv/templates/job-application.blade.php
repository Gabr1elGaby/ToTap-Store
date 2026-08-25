<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Lamaran Kerja</title>
    <style>
        @page {
            margin: 0px;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            background-color: #ffffff;
            color: #1e293b;
            line-height: 1.45;
            word-wrap: break-word;
            word-break: break-word;
        }
        
        /* Continuous Sidebar Background across all pages */
        .sidebar-bg {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 195pt;
            background-color: #1e293b;
            z-index: -1000;
        }
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            width: 195pt;
            padding: 32pt 18pt 20pt 20pt;
            color: #e2e8f0;
        }
        .content {
            margin-left: 212pt;
            padding: 32pt 28pt 24pt 10pt;
            width: 350pt;
            background-color: transparent;
        }

        /* Photo Area */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 18pt;
        }
        .photo {
            width: 85pt;
            height: 85pt;
            border: 3pt solid #f59e0b;
            object-fit: cover;
            background-color: #fff;
            display: block;
            margin: 0 auto;
        }

        /* Left Col Headers */
        .left-header {
            background-color: #f59e0b;
            color: #1e293b;
            font-size: 9.5pt;
            font-weight: bold;
            padding: 4pt 10pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .left-header:first-of-type {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 8pt;
            font-size: 8.5pt;
            width: 155pt;
            line-height: 1.35;
        }
        .contact-label {
            font-weight: bold;
            color: #f59e0b;
            text-transform: uppercase;
            font-size: 7pt;
            display: block;
            margin-bottom: 1pt;
        }
        .contact-value {
            color: #f8fafc;
            word-wrap: break-word;
            word-break: break-all;
        }

        /* Right Col Elements */
        .name {
            font-size: 20pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0 0 2pt 0;
            letter-spacing: 0.5px;
            line-height: 1.15;
        }
        .job-title {
            font-size: 10pt;
            color: #f59e0b;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 14pt;
            letter-spacing: 1px;
        }
        .right-header {
            font-size: 11pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            border-left: 4pt solid #f59e0b;
            padding-left: 8pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            letter-spacing: 0.5px;
        }
        .right-header:first-of-type {
            margin-top: 0;
        }
        
        .profile-text {
            font-size: 9pt;
            text-align: justify;
            margin-bottom: 14pt;
            line-height: 1.4;
        }

        .item {
            margin-bottom: 11pt;
            page-break-inside: avoid;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2pt;
            border-collapse: collapse;
        }
        .item-title {
            font-weight: bold;
            font-size: 10pt;
            color: #1e293b;
        }
        .item-date {
            text-align: right;
            font-size: 8.5pt;
            color: #64748b;
            font-weight: bold;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 9pt;
            color: #475569;
            font-weight: bold;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8.5pt;
            color: #334155;
            text-align: justify;
            line-height: 1.4;
        }

        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 155pt;
        }
        .skill-list li {
            margin-bottom: 4pt;
            font-size: 8.5pt;
            color: #f8fafc;
        }
        .skill-list li::before {
            content: "■ ";
            color: #f59e0b;
            font-size: 7pt;
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
        <div class="left-header">Keahlian</div>
        <div style="margin-bottom: 14pt; width: 155pt;">
            @foreach($hard_skills as $skill)
            <div style="margin-bottom: 6pt;">
                <div style="font-size: 8.5pt; margin-bottom: 2pt; color: #f8fafc;">{{ $skill->name ?? '' }}</div>
                <div style="width: 155pt; background-color: #334155; height: 3.5pt; border-radius: 2pt;">
                    <div style="width: {{ $skill->level }}%; background-color: #f59e0b; height: 3.5pt; border-radius: 2pt;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if(count($soft_skills) > 0)
        <div class="left-header">Keahlian Lain</div>
        <ul class="skill-list">
            @foreach($soft_skills as $skill)
            <li>{{ $skill->name ?? '' }}</li>
            @endforeach
        </ul>
        @endif
    </div>

    <!-- MAIN RIGHT CONTENT -->
    <div class="content">
        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="job-title">{{ $data->job_title ?? 'POSISI YANG DILAMAR' }}</div>
        
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
            <div class="item-subtitle">{{ $exp->company ?? '' }} @if(!empty($exp->location)) | {{ $exp->location }} @endif</div>
            <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
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

        @if(count($organizations) > 0)
        <div class="right-header">Pengalaman Organisasi</div>
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
            <div class="item-desc">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
        </div>
        @endforeach
        @endif
    </div>
</body>
</html>
