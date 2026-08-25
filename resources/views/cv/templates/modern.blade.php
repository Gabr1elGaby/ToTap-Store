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
            font-size: 10pt;
            background-color: #ffffff;
            color: #333333;
            line-height: 1.5;
        }
        
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            width: 35%;
            min-height: 100%;
            background-color: #1d2b38;
            color: #ffffff;
            padding-bottom: 40px;
        }
        
        .photo-container {
            text-align: center;
            padding-top: 40px;
            padding-bottom: 25px;
        }
        .photo-wrapper {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background-color: #ffffff;
            margin: 0 auto;
            text-align: center;
            line-height: 110px;
            font-size: 32pt;
            font-weight: bold;
            color: #1d2b38;
            overflow: hidden;
            border: 3px solid #ffffff;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .left-block {
            background-color: #ffffff;
            color: #111827;
            margin: 0 16px 20px 16px;
            border-radius: 16px;
            padding: 20px 18px 20px 22px;
        }
        
        .left-heading-container {
            text-align: center;
            margin-bottom: 14px;
        }
        .left-heading {
            background-color: #1d2b38;
            color: #ffffff;
            padding: 5px 22px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 9.5pt;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .contact-item {
            font-size: 8.5pt;
            margin-bottom: 12px;
            line-height: 1.35;
            color: #111827;
            text-align: left;
            padding-left: 4px;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-item strong {
            font-size: 7pt;
            text-transform: uppercase;
            color: #6b7280;
            display: block;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        .contact-val {
            color: #111827;
            font-weight: 600;
            display: block;
        }
        
        .skill-item {
            font-size: 8.5pt;
            margin-bottom: 7px;
            color: #111827;
            text-align: left;
            line-height: 1.35;
            padding-left: 4px;
        }
        .skill-item::before {
            content: "•";
            color: #1d2b38;
            font-weight: bold;
            margin-right: 6px;
        }
        
        .content {
            margin-left: 35%;
            width: 65%;
            padding: 40px 35px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        
        .name {
            font-size: 24pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.2;
        }
        .job-title {
            font-size: 11pt;
            color: #4b5563;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-top: 5px;
        }
        .header-line {
            border: 0;
            border-top: 2px solid #111827;
            margin-top: 15px;
            margin-bottom: 25px;
        }
        
        .right-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .right-heading-line {
            border: 0;
            border-top: 1px solid #111827;
            margin-top: 0px;
            margin-bottom: 15px;
        }
        
        .item-block {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .item-meta {
            font-size: 8.5pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 2px;
        }
        .item-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        .item-subtitle {
            font-size: 10pt;
            color: #4b5563;
            margin-bottom: 6px;
        }
        .item-desc {
            font-size: 10pt;
            color: #4b5563;
            text-align: justify;
        }
        .summary {
            font-size: 10pt;
            color: #4b5563;
            text-align: justify;
            margin-bottom: 35px;
        }
        
    </style>
</head>
<body>
    <!-- LEFT SIDEBAR -->
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
        <div class="left-block">
            <div class="left-heading-container">
                <span class="left-heading">Kontak</span>
            </div>
            @if(!empty($data->phone))
            <div class="contact-item">
                <strong>Telepon / WA</strong>
                <span class="contact-val">{{ $data->phone }}</span>
            </div>
            @endif
            @if(!empty($data->email))
            <div class="contact-item">
                <strong>Email</strong>
                <span class="contact-val">{{ $data->email }}</span>
            </div>
            @endif
            @if($getVal($data, 'address', 'location') !== '')
            <div class="contact-item">
                <strong>Domisili</strong>
                <span class="contact-val">{{ $getVal($data, 'address', 'location') }}</span>
            </div>
            @endif
            @if(!empty($data->linkedin))
            <div class="contact-item">
                <strong>LinkedIn</strong>
                <span class="contact-val">{{ $data->linkedin }}</span>
            </div>
            @endif
            @if(!empty($data->website))
            <div class="contact-item">
                <strong>Website / Portofolio</strong>
                <span class="contact-val">{{ $data->website }}</span>
            </div>
            @endif
        </div>
        
        <!-- SKILLS BLOCK -->
        @if(count($skills) > 0)
        <div class="left-block">
            <div class="left-heading-container">
                <span class="left-heading">Keahlian</span>
            </div>
            @foreach($skills as $skill)
            <div class="skill-item">
                {{ $skill->name ?? '' }}
            </div>
            @endforeach
        </div>
        @endif
        
        <!-- CERTIFICATES BLOCK -->
        @if(count($certificates) > 0)
        <div class="left-block">
            <div class="left-heading-container">
                <span class="left-heading">Sertifikasi</span>
            </div>
            @foreach($certificates as $cert)
            <div class="skill-item">
                <strong>{{ $cert->name ?? '' }}</strong><br>
                {{ $cert->year ?? '' }}
            </div>
            @endforeach
        </div>
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
        <hr class="right-heading-line">
        @foreach($educations as $edu)
        <div class="item-block">
            <div class="item-meta">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</div>
            <div class="item-title">{{ $edu->institution ?? '' }}</div>
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
        <div style="height: 15px;"></div>
        @endif

        <!-- PENGALAMAN KERJA -->
        @if(count($experiences) > 0)
        <div class="right-heading">Pengalaman Kerja</div>
        <hr class="right-heading-line">
        @foreach($experiences as $exp)
        <div class="item-block">
            <div class="item-meta">{{ $exp->start_year ?? '' }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_year }}</div>
            <div class="item-title">{{ $exp->position ?? '' }}</div>
            <div class="item-subtitle">{{ $exp->company ?? '' }} @if(!empty($exp->location)) | {{ $exp->location }} @endif</div>
            <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
        </div>
        @endforeach
        <div style="height: 15px;"></div>
        @endif
        
        <!-- MAGANG -->
        @if(count($internships) > 0)
        <div class="right-heading">Pengalaman Magang</div>
        <hr class="right-heading-line">
        @foreach($internships as $int)
        <div class="item-block">
            <div class="item-meta">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</div>
            <div class="item-title">{{ $int->position ?? '' }}</div>
            <div class="item-subtitle">{{ $int->company ?? '' }} @if(!empty($int->location)) | {{ $int->location }} @endif</div>
            <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
        </div>
        @endforeach
        <div style="height: 15px;"></div>
        @endif

        <!-- PENGALAMAN ORGANISASI -->
        @if(count($organizations) > 0)
        <div class="right-heading">Pengalaman Organisasi</div>
        <hr class="right-heading-line">
        @foreach($organizations as $org)
        <div class="item-block">
            <div class="item-meta">{{ $org->start_year ?? '' }} - {{ $org->end_year ?? '' }}</div>
            <div class="item-title">{{ $org->role ?? '' }}</div>
            <div class="item-subtitle">{{ $org->name ?? '' }}</div>
            <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
        </div>
        @endforeach
        <div style="height: 15px;"></div>
        @endif

        <!-- PROYEK & PORTOFOLIO -->
        @if(count($projects) > 0)
        <div class="right-heading">Proyek & Portofolio</div>
        <hr class="right-heading-line">
        @foreach($projects as $proj)
        <div class="item-block">
            <div class="item-meta">{{ $proj->year ?? $proj->link ?? '' }}</div>
            <div class="item-title">{{ $proj->name ?? '' }}</div>
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
        <div style="height: 15px;"></div>
        @endif
    </div>
</body>
</html>
