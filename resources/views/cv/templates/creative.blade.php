<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Kreatif</title>
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
            color: #334155;
            line-height: 1.45;
            word-wrap: break-word;
            word-break: break-word;
        }
        
        /* PAGE 1: 2-COLUMN TABLE */
        .p1-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .p1-left {
            width: 34%;
            background-color: #0f172a;
            color: #cbd5e1;
            padding: 32pt 18pt 30pt 18pt;
            vertical-align: top;
        }
        .p1-right {
            width: 66%;
            background-color: #ffffff;
            padding: 32pt 30pt 30pt 25pt;
            vertical-align: top;
        }
        
        /* Photo centered */
        .photo-container {
            text-align: center;
            margin-bottom: 14pt;
        }
        .photo-wrapper {
            width: 85pt;
            height: 85pt;
            border-radius: 50%;
            border: 3pt solid #38bdf8;
            overflow: hidden;
            display: block;
            margin: 0 auto;
            text-align: center;
            background-color: #1e293b;
        }
        .photo {
            width: 85pt;
            height: 85pt;
            display: block;
            margin: 0 auto;
        }

        /* Identity */
        .name {
            font-size: 14pt;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            text-transform: uppercase;
            margin: 0 auto 3pt auto;
            line-height: 1.2;
        }
        .job-title {
            font-size: 8.5pt;
            color: #38bdf8;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 auto 16pt auto;
            font-weight: bold;
        }

        /* Left Section */
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
            margin-bottom: 8pt;
            font-size: 8.5pt;
            line-height: 1.35;
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

        /* Section Headings */
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
        
        .profile-text {
            font-size: 9pt;
            text-align: justify;
            margin-bottom: 14pt;
            line-height: 1.4;
        }

        /* Timeline Items */
        .item {
            margin-bottom: 10pt;
            page-break-inside: avoid;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2pt;
            border-collapse: collapse;
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

        /* PAGE 2 FULL WIDTH WHITE CONTAINER */
        .page-break {
            page-break-before: always;
        }
        .page2-container {
            width: 100%;
            background-color: #ffffff;
            padding: 35pt 35pt 30pt 35pt;
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

        // Check if we need Page 2 for secondary sections
        $hasPage2 = (count($educations) > 0 || count($organizations) > 0 || count($internships) > 0 || count($certificates) > 0);
    @endphp

    <!-- PAGE 1: 2-COLUMN EXECUTIVE RESUME -->
    <table class="p1-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- LEFT COLUMN (DARK NAVY SIDEBAR) -->
            <td class="p1-left">
                @if(!empty($data->photo))
                <div class="photo-container">
                    <div class="photo-wrapper">
                        <img src="{{ $data->photo }}" class="photo">
                    </div>
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
                <div style="margin-bottom: 14pt;">
                    @foreach($hard_skills as $skill)
                    <div style="margin-bottom: 6pt;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 2pt;">
                            <tr>
                                <td style="font-size: 8.5pt; color: #ffffff;">{{ $skill->name ?? '' }}</td>
                                <td align="right" style="font-size: 8pt; color: #38bdf8; font-weight: bold;">{{ $skill->level ?? '' }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #334155; height: 3.5pt; border-radius: 2pt;">
                            <div style="width: {{ $skill->level ?? '' }}%; background-color: #38bdf8; height: 3.5pt; border-radius: 2pt;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(count($soft_skills) > 0)
                <div class="left-header">Keahlian Interpersonal</div>
                <ul class="skill-list" style="margin-bottom: 14pt;">
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
            </td>

            <!-- RIGHT COLUMN (WHITE MAIN CONTENT) -->
            <td class="p1-right">
                @if(!empty($data->profile))
                <div class="right-header">Tentang Saya</div>
                <div class="profile-text">
                    {!! nl2br(e($data->profile)) !!}
                </div>
                @endif

                <!-- PENGALAMAN KERJA -->
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

                <!-- PROYEK & PORTOFOLIO -->
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

                @if(!$hasPage2)
                    <!-- If no secondary sections, render education & certs on page 1 if any -->
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
                @endif
            </td>
        </tr>
    </table>

    <!-- PAGE 2: FULL WIDTH PURE WHITE (NO BLUE BAR, CLEAN TOP MARGIN) -->
    @if($hasPage2)
    <div class="page-break"></div>
    <div class="page2-container">
        <!-- RIWAYAT PENDIDIKAN -->
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

        <!-- PENGALAMAN MAGANG -->
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
            <div class="item-subtitle">{{ $int->company ?? '' }} @if(!empty($int->location)) | {{ $int->location ?? '' }} @endif</div>
            <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
        </div>
        @endforeach
        @endif

        <!-- PENGALAMAN ORGANISASI -->
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

        <!-- SERTIFIKASI & PELATIHAN -->
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
    @endif
</body>
</html>
