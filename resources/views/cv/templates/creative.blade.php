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
$tools         = $getCol('tools');

$initials = '';
if (!empty($data->name)) {
    $words = explode(' ', trim($data->name));
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
}
if (empty($initials)) {
    $initials = 'CV';
}

$hasPage2 = (count($internships) > 0 || count($organizations) > 0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Kreatif Desain</title>
    <style>
        @page {
            margin: 0px;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            position: relative;
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9.5pt;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.45;
            word-wrap: break-word;
            word-break: break-word;
        }
        table, tr, td, tbody {
            page-break-inside: auto !important;
        }
        table {
            border-collapse: collapse;
            table-layout: fixed;
            width: 100%;
        }
        td {
            vertical-align: top;
            word-wrap: break-word;
            word-break: break-word;
        }
        
        .sidebar-td {
            width: 32%;
            background-color: #0f172a;
            color: #cbd5e1;
            padding: 30pt 16pt 25pt 16pt;
        }
        
        .left-header {
            font-size: 9.5pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #38bdf8;
            padding-bottom: 3pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            display: block;
            width: 100%;
        }
        .left-header:first-of-type {
            margin-top: 0;
        }
        
        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .contact-list li {
            margin-bottom: 8pt;
            font-size: 8.5pt;
            line-height: 1.35;
            width: 100%;
        }
        .contact-label {
            color: #38bdf8;
            font-weight: bold;
            font-size: 7pt;
            text-transform: uppercase;
            display: block;
            margin-bottom: 1pt;
        }
        .contact-value {
            color: #f8fafc;
            word-wrap: break-word;
            word-break: break-all;
        }
        
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
            width: 100%;
        }
        .skill-list li {
            margin-bottom: 5pt;
            font-size: 8.5pt;
            color: #f1f5f9;
        }
        .skill-list li::before {
            content: "• ";
            color: #38bdf8;
            font-weight: bold;
        }

        .content-td {
            width: 68%;
            background-color: #ffffff;
            padding: 30pt 25pt 25pt 22pt;
        }
        
        .right-header {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 3pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
        }
        .right-header:first-of-type {
            margin-top: 0;
        }
        
        .item {
            margin-bottom: 11pt;
            page-break-inside: auto;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2pt;
        }
        .item-title {
            font-size: 10pt;
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

        .page2-container {
            page-break-before: always;
            width: 100%;
            background-color: #ffffff;
            padding: 35pt 35pt 30pt 35pt;
        }
    </style>
</head>
<body>
    <!-- Full-height sidebar background strip for 100% A4 coverage -->
    <div style="position: absolute; top: 0px; left: 0px; width: 32%; height: 842pt; background-color: #0f172a; z-index: -100;"></div>

    <table class="main-table" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse; table-layout: fixed;">
        <tr>
            <td class="sidebar-td">
                <div style="text-align: center; margin-bottom: 16pt;">
                    @if(!empty($data->photo))
                        <img src="{{ $data->photo }}" style="width: 75pt; height: 75pt; border-radius: 50%; border: 3pt solid #38bdf8; display: block; margin: 0 auto; object-fit: cover;">
                    @else
                        <div style="width: 75pt; height: 75pt; border-radius: 50%; border: 3pt solid #38bdf8; background-color: #1e293b; margin: 0 auto; text-align: center; line-height: 75pt; font-size: 22pt; font-weight: bold; color: #38bdf8;">
                            {{ $initials }}
                        </div>
                    @endif
                </div>

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

                @if(count($skills) > 0)
                <div class="left-header">Keahlian (Skills)</div>
                <div style="width: 100%;">
                    @foreach($skills as $skill)
                        @php
                            $lvl = null;
                            if (isset($skill->level) && is_numeric(trim($skill->level))) {
                                $lvl = (int) trim($skill->level);
                                if ($lvl > 100) $lvl = 100;
                                if ($lvl < 5) $lvl = 5;
                            }
                        @endphp
                        
                        @if($lvl !== null)
                        <div style="margin-bottom: 7pt; width: 100%;">
                            <table style="width: 100%; margin-bottom: 2pt; border-collapse: collapse;">
                                <tr>
                                    <td style="font-size: 8.5pt; font-weight: bold; color: #ffffff; text-align: left; vertical-align: middle;">
                                        {{ $skill->name ?? '' }}
                                    </td>
                                    <td style="font-size: 7.5pt; font-weight: bold; color: #38bdf8; text-align: right; width: 35pt; vertical-align: middle;">
                                        {{ $lvl }}%
                                    </td>
                                </tr>
                            </table>
                            <div style="width: 100%; height: 4pt; background-color: #334155; border-radius: 2pt; overflow: hidden;">
                                <div style="width: {{ $lvl }}%; height: 4pt; background-color: #38bdf8; border-radius: 2pt;"></div>
                            </div>
                        </div>
                        @else
                        <div style="margin-bottom: 4pt; font-size: 8.5pt; color: #f8fafc;">
                            <span style="color: #38bdf8; font-weight: bold;">•</span> {{ $skill->name ?? '' }}
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif

                @if(count($tools) > 0)
                <div class="left-header">Tools & Software</div>
                <ul class="skill-list">
                    @foreach($tools as $tool)
                    <li>{{ $tool->name ?? '' }}</li>
                    @endforeach
                </ul>
                @endif

                @if(count($certificates) > 0)
                <div class="left-header">Sertifikasi</div>
                @foreach($certificates as $cert)
                <div style="margin-bottom: 8pt;">
                    <div style="font-weight: bold; color: #ffffff; font-size: 8.5pt;">{{ $cert->name ?? '' }}</div>
                    @if(!empty($cert->year))
                    <div style="font-size: 7.5pt; color: #94a3b8;">{{ $cert->year }}</div>
                    @endif
                </div>
                @endforeach
                @endif
            </td>

            <td class="content-td">
                <div style="font-size: 20pt; font-weight: bold; color: #0f172a; text-transform: uppercase; line-height: 1.15; margin-bottom: 2pt;">
                    {{ !empty($data->name) ? $data->name : 'NAMA LENGKAP' }}
                </div>
                <div style="font-size: 10pt; font-weight: bold; color: #0284c7; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 10pt;">
                    {{ !empty($data->job_title) ? $data->job_title : 'PROFESI / ROLE' }}
                </div>
                <hr style="border: 0; border-top: 1.5pt solid #cbd5e1; margin-bottom: 14pt;">

                @if(!empty($data->profile))
                <div class="right-header">Tentang Saya</div>
                <div style="font-size: 9pt; color: #334155; text-align: justify; margin-bottom: 14pt; line-height: 1.4;">
                    {!! nl2br(e($data->profile)) !!}
                </div>
                @endif

                @if(count($experiences) > 0)
                <div class="right-header">Pengalaman Kerja</div>
                @foreach($experiences as $exp)
                <div class="item">
                    <table class="item-title-row" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $exp->position ?? '' }}</td>
                            <td class="item-date" style="width: 30%;">{{ $exp->start_year ?? '' }} - {{ $exp->is_current ? 'Sekarang' : ($exp->end_year ?? '') }}</td>
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
                            <td class="item-title" style="width: 70%;">{{ $edu->institution ?? '' }}</td>
                            <td class="item-date" style="width: 30%;">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</td>
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

                @if(count($projects) > 0)
                <div class="right-header">Proyek & Portofolio</div>
                @foreach($projects as $proj)
                <div class="item">
                    <table class="item-title-row" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $proj->name ?? '' }}</td>
                            <td class="item-date" style="width: 30%;">{{ $proj->year ?? $proj->link ?? '' }}</td>
                        </tr>
                    </table>
                    @php
                        $projSub = array_filter([$proj->role ?? '', $proj->technologies ?? '', (!empty($proj->year) ? $proj->link ?? '' : '')]);
                    @endphp
                    @if(!empty($projSub))
                    <div class="item-subtitle">{{ implode(' | ', $projSub) }}</div>
                    @endif
                    <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
                </div>
                @endforeach
                @endif
                @if(count($internships) > 0)
                <div class="right-header">Pengalaman Magang</div>
                @foreach($internships as $int)
                <div class="item">
                    <table class="item-title-row" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $int->position ?? '' }}</td>
                            <td class="item-date" style="width: 30%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
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
                            <td class="item-title" style="width: 70%;">{{ $org->role ?? '' }}</td>
                            <td class="item-date" style="width: 30%;">{{ $org->start_year ?? '' }} - {{ $org->end_year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $org->name ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
