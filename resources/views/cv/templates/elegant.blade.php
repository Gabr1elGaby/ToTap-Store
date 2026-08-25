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
    <title>CV Elegant</title>
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
        
        .header {
            background-color: #264653;
            color: #ffffff;
            padding: 35px 35px 20px 35px;
            border-bottom: 10px solid #d4af37;
        }
        
        .photo-wrapper {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid #d4af37;
            background-color: #ffffff;
            overflow: hidden;
            display: inline-block;
            text-align: center;
            line-height: 120px;
            font-size: 32pt;
            font-weight: bold;
            color: #264653;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #d4af37;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .job-title {
            font-size: 11pt;
            color: #e2e8f0;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .summary {
            font-size: 9.5pt;
            color: #e2e8f0;
            text-align: justify;
            line-height: 1.4;
        }
        
        .sidebar {
            position: absolute;
            left: 0;
            width: 32%;
            background-color: #2a9d8f;
            color: #ffffff;
            padding: 30px 20px;
            box-sizing: border-box;
        }
        .content {
            margin-left: 32%;
            width: 68%;
            background-color: #ffffff;
            padding: 30px 30px;
            box-sizing: border-box;
        }
        
        .left-heading {
            font-size: 12pt;
            font-weight: bold;
            color: #ffffff;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 4px;
            margin-bottom: 15px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .left-heading:first-child {
            margin-top: 0px;
        }
        
        .contact-item {
            margin-bottom: 12px;
            font-size: 9pt;
            color: #e2e8f0;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-item strong {
            color: #ffffff;
            font-size: 7.5pt;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }
        
        .skill-item {
            margin-bottom: 8px;
            font-size: 9.5pt;
            color: #e2e8f0;
        }
        .skill-item::before {
            content: "■";
            color: #d4af37;
            font-size: 8pt;
            margin-right: 8px;
        }
        
        .right-heading {
            font-size: 12.5pt;
            font-weight: bold;
            color: #264653;
            border-bottom: 2px solid #264653;
            padding-bottom: 4px;
            margin-bottom: 15px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .right-heading:first-child {
            margin-top: 0px;
        }
        
        .item-block {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .item-title {
            font-size: 11pt;
            font-weight: bold;
            color: #264653;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .item-meta {
            font-size: 9.5pt;
            color: #333333;
            margin-bottom: 6px;
        }
        .item-desc {
            font-size: 9.5pt;
            color: #4b5563;
            text-align: justify;
        }
    </style>
</head>
<body>
    <!-- TOP HEADER -->
    <div class="header">
        <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td width="140" align="center" valign="top">
                    <div class="photo-wrapper">
                        @if(!empty($data->photo))
                        <img src="{{ $data->photo ?? '' }}">
                        @else
                        {{ $initials }}
                        @endif
                    </div>
                </td>
                <td valign="top" style="padding-left: 25px;">
                    <div class="name">{{ $data->name ?? '' }}</div>
                    <div class="job-title">{{ $data->job_title ?? '' }}</div>
                    <div class="summary">{{ $data->summary ?? '' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <!-- LEFT SIDEBAR -->
    <div class="sidebar">
        <div class="left-heading">Kontak</div>
        @if(!empty($data->phone))
        <div class="contact-item">
            <strong>Telepon / WA</strong>
            {{ $data->phone }}
        </div>
        @endif
        @if(!empty($data->email))
        <div class="contact-item">
            <strong>Email</strong>
            {{ $data->email }}
        </div>
        @endif
        @if($getVal($data, 'address', 'location') !== '')
        <div class="contact-item">
            <strong>Domisili</strong>
            {{ $getVal($data, 'address', 'location') }}
        </div>
        @endif
        @if(!empty($data->linkedin))
        <div class="contact-item">
            <strong>LinkedIn</strong>
            {{ $data->linkedin }}
        </div>
        @endif
        @if(!empty($data->website))
        <div class="contact-item">
            <strong>Website / Portofolio</strong>
            {{ $data->website }}
        </div>
        @endif
        @if(!empty($data->social_media))
        <div class="contact-item">
            <strong>Media Sosial</strong>
            {{ $data->social_media }}
        </div>
        @endif

        @if(count($skills) > 0)
        <div class="left-heading">Keahlian</div>
        @foreach($skills as $skill)
        <div class="skill-item">
            {{ $skill->name ?? '' }}
        </div>
        @endforeach
        @endif

        @if(count($certificates) > 0)
        <div class="left-heading">Sertifikasi</div>
        @foreach($certificates as $cert)
        <div class="contact-item">
            <strong>{{ $cert->name ?? '' }}</strong>
            {{ $cert->year ?? '' }}
        </div>
        @endforeach
        @endif
    </div>

    <!-- MAIN RIGHT CONTENT -->
    <div class="content">
        <!-- PENGALAMAN KERJA -->
        @if(count($experiences) > 0)
        <div class="right-heading">Pengalaman Kerja</div>
        @foreach($experiences as $exp)
        <div class="item-block">
            <div class="item-title">{{ $exp->position ?? '' }}</div>
            <div class="item-meta">{{ $exp->company ?? '' }} | {{ $exp->start_year ?? '' }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_year }}</div>
            <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
        </div>
        @endforeach
        @endif
        
        <!-- RIWAYAT PENDIDIKAN -->
        @if(count($educations) > 0)
        <div class="right-heading">Riwayat Pendidikan</div>
        @foreach($educations as $edu)
        <div class="item-block">
            <div class="item-title">{{ $edu->institution ?? '' }}</div>
            @php
                $deg = $edu->degree ?? '';
                $maj = $getVal($edu, 'major', 'field');
            @endphp
            <div class="item-meta">{{ $deg }}{{ $maj !== '' ? ($deg !== '' ? ' - ' : '') . $maj : '' }} | {{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</div>
            @if(!empty($edu->description))
            <div class="item-desc">{!! nl2br(e($edu->description)) !!}</div>
            @endif
        </div>
        @endforeach
        @endif

        <!-- PENGALAMAN MAGANG -->
        @if(count($internships) > 0)
        <div class="right-heading">Pengalaman Magang</div>
        @foreach($internships as $int)
        <div class="item-block">
            <div class="item-title">{{ $int->position ?? '' }}</div>
            <div class="item-meta">{{ $int->company ?? '' }} | {{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</div>
            <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
        </div>
        @endforeach
        @endif

        <!-- PENGALAMAN ORGANISASI -->
        @if(count($organizations) > 0)
        <div class="right-heading">Pengalaman Organisasi</div>
        @foreach($organizations as $org)
        <div class="item-block">
            <div class="item-title">{{ $org->role ?? '' }}</div>
            <div class="item-meta">{{ $org->name ?? '' }} | {{ $org->start_year ?? '' }} - {{ $org->end_year ?? '' }}</div>
            <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
        </div>
        @endforeach
        @endif

        <!-- PROYEK & PORTOFOLIO -->
        @if(count($projects) > 0)
        <div class="right-heading">Proyek & Portofolio</div>
        @foreach($projects as $proj)
        <div class="item-block">
            <div class="item-title">{{ $proj->name ?? '' }}</div>
            @php
                $projSub = array_filter([$proj->role ?? '', $proj->technologies ?? '', (!empty($proj->year) ? $proj->link ?? '' : '')]);
            @endphp
            <div class="item-meta">{{ implode(' | ', $projSub) }}</div>
            @if(!empty($proj->description))
            <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
            @endif
        </div>
        @endforeach
        @endif
    </div>
</body>
</html>
