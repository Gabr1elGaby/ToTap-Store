<?php
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

$initials = '';
if (!empty($data->name)) {
    $words = explode(' ', $data->name);
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Modern</title>
    <style>
        @page { margin: 0px; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0; padding: 0;
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
        
        .photo-container {
            text-align: center;
            margin-bottom: 22px;
        }
        .photo-wrapper {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background-color: #1e293b;
            margin: 0 auto;
            text-align: center;
            line-height: 110px;
            font-size: 32pt;
            font-weight: bold;
            color: #38bdf8;
            overflow: hidden;
            border: 3px solid #38bdf8;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .sidebar-heading {
            font-size: 10.5pt;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #38bdf8;
            padding-bottom: 4px;
            margin-bottom: 12px;
            margin-top: 22px;
        }
        .sidebar-heading:first-of-type {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 10px;
            font-size: 8.5pt;
            line-height: 1.35;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-label {
            font-size: 7pt;
            text-transform: uppercase;
            color: #38bdf8;
            display: block;
            margin-bottom: 1px;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .contact-val {
            color: #f8fafc;
            font-weight: 500;
            display: block;
        }
        
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            font-size: 8.5pt;
            margin-bottom: 6px;
            color: #f1f5f9;
            line-height: 1.35;
        }
        .skill-list li::before {
            content: "•";
            color: #38bdf8;
            font-weight: bold;
            margin-right: 6px;
        }
        
        .cert-item {
            margin-bottom: 8px;
            font-size: 8.5pt;
            color: #f1f5f9;
        }
        .cert-title {
            font-weight: 700;
            color: #ffffff;
        }
        .cert-year {
            font-size: 7.5pt;
            color: #94a3b8;
        }
        
        .content {
            margin-left: 34%;
            width: 66%;
            padding: 35px 30px;
            box-sizing: border-box;
            background-color: transparent;
        }
        
        .name {
            font-size: 22pt;
            font-weight: 900;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.15;
            margin-bottom: 3px;
        }
        .job-title {
            font-size: 10.5pt;
            font-weight: 700;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 12px;
        }
        .header-line {
            border: 0;
            border-top: 2px solid #e2e8f0;
            margin-bottom: 18px;
        }
        
        .right-heading {
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
        .right-heading:first-of-type {
            margin-top: 0;
        }
        
        .item-block {
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .item-header-table {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-size: 10.5pt;
            font-weight: 700;
            color: #0f172a;
        }
        .item-date {
            font-size: 8.5pt;
            font-weight: 700;
            color: #0284c7;
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
        .summary {
            font-size: 9.5pt;
            color: #334155;
            text-align: justify;
            margin-bottom: 16px;
            line-height: 1.45;
        }
    </style>
</head>
<body>
    <!-- CONTINUOUS SIDEBAR BACKGROUND -->
    <div class="sidebar-bg"></div>

    <!-- LEFT SIDEBAR CONTENT -->
    <div class="sidebar">
        <div class="photo-container">
            <div class="photo-wrapper">
                @if(!empty($data->photo))
                <img src="{{ $data->photo }}">
                @else
                {{ $initials }}
                @endif
            </div>
        </div>
        
        <!-- CONTACT BLOCK -->
        <div class="sidebar-heading">Kontak</div>
        @if(!empty($data->phone))
        <div class="contact-item">
            <span class="contact-label">Telepon / WA</span>
            <span class="contact-val">{{ $data->phone }}</span>
        </div>
        @endif
        @if(!empty($data->email))
        <div class="contact-item">
            <span class="contact-label">Email</span>
            <span class="contact-val">{{ $data->email }}</span>
        </div>
        @endif
        @if($getVal($data, 'address', 'location') !== '')
        <div class="contact-item">
            <span class="contact-label">Domisili</span>
            <span class="contact-val">{{ $getVal($data, 'address', 'location') }}</span>
        </div>
        @endif
        @if(!empty($data->linkedin))
        <div class="contact-item">
            <span class="contact-label">LinkedIn</span>
            <span class="contact-val">{{ $data->linkedin }}</span>
        </div>
        @endif
        @if(!empty($data->website))
        <div class="contact-item">
            <span class="contact-label">Website / Portofolio</span>
            <span class="contact-val">{{ $data->website }}</span>
        </div>
        @endif
        @if(!empty($data->social_media))
        <div class="contact-item">
            <span class="contact-label">Media Sosial</span>
            <span class="contact-val">{{ $data->social_media }}</span>
        </div>
        @endif
        
        <!-- SKILLS BLOCK -->
        @if(count($skills) > 0)
        <div class="sidebar-heading">Keahlian</div>
        <ul class="skill-list">
            @foreach($skills as $skill)
            <li>{{ $skill->name ?? '' }}</li>
            @endforeach
        </ul>
        @endif
        
        <!-- CERTIFICATES BLOCK -->
        @if(count($certificates) > 0)
        <div class="sidebar-heading">Sertifikasi</div>
        @foreach($certificates as $cert)
        <div class="cert-item">
            <div class="cert-title">{{ $cert->name ?? '' }}</div>
            @if(!empty($cert->year))
            <div class="cert-year">{{ $cert->year }}</div>
            @endif
        </div>
        @endforeach
        @endif
    </div>

    <!-- MAIN RIGHT CONTENT -->
    <div class="content">
        <!-- HEADER NAME & TITLE -->
        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="job-title">{{ $data->job_title ?? 'POSISI / PEKERJAAN' }}</div>
        <hr class="header-line">
        
        <!-- PROFILE / SUMMARY -->
        @if(!empty($data->summary) || !empty($data->profile))
        <div class="summary">
            {!! nl2br(e($data->summary ?? $data->profile)) !!}
        </div>
        @endif
        
        <!-- RIWAYAT PENDIDIKAN -->
        @if(count($educations) > 0)
        <div class="right-heading">Riwayat Pendidikan</div>
        @foreach($educations as $edu)
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
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

        <!-- PENGALAMAN KERJA -->
        @if(count($experiences) > 0)
        <div class="right-heading">Pengalaman Kerja</div>
        @foreach($experiences as $exp)
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
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
        
        <!-- MAGANG -->
        @if(count($internships) > 0)
        <div class="right-heading">Pengalaman Magang</div>
        @foreach($internships as $int)
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
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

        <!-- PENGALAMAN ORGANISASI -->
        @if(count($organizations) > 0)
        <div class="right-heading">Pengalaman Organisasi</div>
        @foreach($organizations as $org)
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
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

        <!-- PROYEK & PORTOFOLIO -->
        @if(count($projects) > 0)
        <div class="right-heading">Proyek & Portofolio</div>
        @foreach($projects as $proj)
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
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
    </div>
</body>
</html>
