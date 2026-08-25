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
    if (isset($uData['cv'][$key]) && is_array($uData['cv'][$key])) {
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
        html, body { height: 100%; margin: 0; padding: 0; }
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
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
            min-height: 1123px;
        }
        
        .left-col {
            width: 35%;
            background-color: #1d2b38;
            color: #ffffff;
            vertical-align: top;
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
            margin-left: 20px;
            margin-right: 0px;
            border-top-left-radius: 20px;
            border-bottom-left-radius: 20px;
            padding: 20px 15px 20px 20px;
            margin-bottom: 20px;
        }
        
        .left-heading-container {
            text-align: center;
            margin-bottom: 12px;
        }
        .left-heading {
            background-color: #1d2b38;
            color: #ffffff;
            padding: 5px 25px;
            border-radius: 12px;
            font-weight: bold;
            font-size: 10pt;
            display: inline-block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .contact-item {
            font-size: 9pt;
            margin-bottom: 10px;
            line-height: 1.3;
            color: #111827;
            text-align: center;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-item strong {
            font-size: 7.5pt;
            text-transform: uppercase;
            color: #6b7280;
            display: block;
            margin-top: 2px;
        }
        
        .skill-item {
            font-size: 9pt;
            margin-bottom: 8px;
            color: #111827;
            text-align: center;
            line-height: 1.3;
        }
        
        .right-col {
            width: 65%;
            background-color: #ffffff;
            vertical-align: top;
            padding: 40px 35px;
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
    <table class="main-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
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
                        {{ $data->phone }}<br><strong>Telepon / WA</strong>
                    </div>
                    @endif
                    @if(!empty($data->email))
                    <div class="contact-item">
                        {{ $data->email }}<br><strong>Email</strong>
                    </div>
                    @endif
                    @if($getVal($data, 'address', 'location') !== '')
                    <div class="contact-item">
                        {{ $getVal($data, 'address', 'location') }}<br><strong>Domisili</strong>
                    </div>
                    @endif
                    @if(!empty($data->linkedin))
                    <div class="contact-item">
                        {{ $data->linkedin }}<br><strong>LinkedIn</strong>
                    </div>
                    @endif
                    @if(!empty($data->website))
                    <div class="contact-item">
                        {{ $data->website }}<br><strong>Website / Portofolio</strong>
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
            </td>
            
            <!-- RIGHT COLUMN -->
            <td class="right-col">
                <div class="name">{{ $data->name ?? '' }}</div>
                <div class="job-title">{{ $data->job_title ?? '' }}</div>
                <hr class="header-line">
                
                @if(!empty($data->profile))
                <div class="right-heading">Tentang Saya</div>
                <hr class="right-heading-line">
                <div class="summary">{!! nl2br(e($data->profile)) !!}</div>
                @endif
                
                @if(count($educations) > 0)
                <div class="right-heading">Riwayat Pendidikan</div>
                <hr class="right-heading-line">
                @foreach($educations as $edu)
                <div class="item-block">
                    <div class="item-meta">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</div>
                    @php
                        $deg = $edu->degree ?? '';
                        $maj = $getVal($edu, 'major', 'field');
                    @endphp
                    <div class="item-title">{{ $deg }}{{ $maj !== '' ? ($deg !== '' ? ' - ' : '') . $maj : '' }}</div>
                    <div class="item-subtitle">{{ $edu->institution ?? '' }}</div>
                    @if(!empty($edu->description))
                    <div class="item-desc">{!! nl2br(e($edu->description)) !!}</div>
                    @endif
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($experiences) > 0)
                <div class="right-heading">Pengalaman Kerja</div>
                <hr class="right-heading-line">
                @foreach($experiences as $exp)
                <div class="item-block">
                    <div class="item-meta">{{ $exp->start_year ?? '' }} - {{ !empty($exp->is_current) ? 'Sekarang' : ($exp->end_year ?? '') }}</div>
                    <div class="item-title">{{ $exp->position ?? '' }}</div>
                    <div class="item-subtitle">{{ $exp->company ?? '' }}{{ !empty($exp->location) ? ' | ' . $exp->location : '' }}</div>
                    @if(!empty($exp->description))
                    <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
                    @endif
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($internships) > 0)
                <div class="right-heading">Pengalaman Magang</div>
                <hr class="right-heading-line">
                @foreach($internships as $int)
                <div class="item-block">
                    <div class="item-meta">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</div>
                    <div class="item-title">{{ $int->position ?? '' }}</div>
                    <div class="item-subtitle">{{ $int->company ?? '' }}{{ !empty($int->location) ? ' | ' . $int->location : '' }}</div>
                    @if(!empty($int->description))
                    <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
                    @endif
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($projects) > 0)
                <div class="right-heading">Proyek & Portofolio</div>
                <hr class="right-heading-line">
                @foreach($projects as $proj)
                <div class="item-block">
                    @if($getVal($proj, 'year') !== '')
                    <div class="item-meta">{{ $getVal($proj, 'year') }}</div>
                    @endif
                    <div class="item-title">{{ $proj->name ?? '' }}</div>
                    @php
                        $projSub = array_filter([$getVal($proj, 'role'), $getVal($proj, 'technologies'), $getVal($proj, 'link')]);
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
                
                @if(count($organizations) > 0)
                <div class="right-heading">Pengalaman Organisasi</div>
                <hr class="right-heading-line">
                @foreach($organizations as $org)
                <div class="item-block">
                    <div class="item-meta">{{ $org->period ?? '' }}</div>
                    <div class="item-title">{{ $org->role ?? '' }}</div>
                    <div class="item-subtitle">{{ $org->organization_name ?? '' }}</div>
                    @if(!empty($org->description))
                    <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
