<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Fresh Graduate</title>
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
        
        /* PAGE 1 SIDEBAR */
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 160pt;
            background-color: #111827;
            padding: 32pt 16pt 20pt 16pt;
            color: #e5e7eb;
        }

        /* Left Col Headers (Gold ribbon style) */
        .left-header {
            background-color: #D4AF37; /* Gold */
            color: #111827;
            font-size: 9.5pt;
            font-weight: bold;
            padding: 4pt 12pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            text-transform: uppercase;
            border-top-right-radius: 12pt;
            border-bottom-right-radius: 12pt;
            width: 135pt;
            letter-spacing: 0.5px;
            display: block;
        }
        .left-header:first-of-type {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 8pt;
            font-size: 8.5pt;
            line-height: 1.35;
            width: 160pt;
        }
        .contact-label {
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
            font-size: 7pt;
            display: block;
            margin-bottom: 1pt;
        }
        .contact-value {
            color: #f3f4f6;
            word-wrap: break-word;
            word-break: break-all;
        }

        /* PAGE 1 MAIN CONTENT */
        .content-p1 {
            margin-left: 225pt;
            padding: 32pt 30pt 24pt 5pt;
            width: 340pt;
        }

        /* Right Col Elements */
        .name {
            font-size: 20pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin: 0 0 2pt 0;
            letter-spacing: 0.5px;
            line-height: 1.15;
        }
        .job-title {
            font-size: 10pt;
            color: #4b5563;
            font-style: italic;
            margin-bottom: 12pt;
        }
        .right-header {
            font-size: 11pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            border-bottom: 1.5pt solid #111827;
            padding-bottom: 3pt;
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
            color: #111827;
        }
        .item-date {
            text-align: right;
            font-size: 8.5pt;
            color: #4b5563;
            white-space: nowrap;
            font-weight: bold;
        }
        .item-subtitle {
            font-size: 9pt;
            color: #374151;
            font-weight: bold;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8.5pt;
            color: #4b5563;
            text-align: justify;
            line-height: 1.4;
        }

        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0 0 14pt 0;
            width: 160pt;
        }
        .skill-list li {
            margin-bottom: 4pt;
            font-size: 8.5pt;
        }
        .skill-list li::before {
            content: "• ";
            color: #D4AF37;
            font-weight: bold;
        }

        /* PAGE 2 FULL WIDTH WHITE CONTAINER */
        .page2-container {
            page-break-before: always;
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

        $hard_skills = $skills->filter(fn($s) => isset($s->level) && $s->level !== '' && $s->level !== null)->all();
        $soft_skills = $skills->filter(fn($s) => !isset($s->level) || $s->level === '' || $s->level === null)->all();

        $hasPage2 = (count($educations) > 0 || count($organizations) > 0 || count($internships) > 0 || count($certificates) > 0);
    @endphp

    <!-- PAGE 1 SIDEBAR (BOTTOM: 0 STRETCHES TO BOTTOM OF PAGE 1) -->
    <div class="sidebar">
        <!-- PHOTO CENTERED -->
        <table width="160pt" cellpadding="0" cellspacing="0" style="margin-bottom: 14pt;">
            <tr>
                <td align="center" style="text-align: center;">
                    @if(!empty($data->photo))
                    <table align="center" cellpadding="0" cellspacing="0" style="margin: 0 auto;">
                        <tr>
                            <td align="center" style="width: 85pt; height: 85pt; border-radius: 50%; border: 3pt solid #D4AF37; overflow: hidden; background-color: #111827; text-align: center; vertical-align: middle;">
                                <img src="{{ $data->photo }}" style="width: 85pt; height: 85pt; display: block; margin: 0 auto;">
                            </td>
                        </tr>
                    </table>
                    @else
                    <div style="height: 20pt;"></div>
                    @endif
                </td>
            </tr>
        </table>

        <div class="left-header">Data Diri</div>
        <div style="margin-bottom: 12pt; width: 160pt;">
            @if(!empty($data->phone))
            <div class="contact-item">
                <span class="contact-label">Telepon</span>
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
        </div>

        <div class="left-header">Media & Web</div>
        <div style="margin-bottom: 12pt; width: 160pt;">
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
        </div>
    </div>

    <!-- PAGE 1: RIGHT COLUMN CONTENT -->
    <div class="content-p1">
        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="job-title">{{ $data->job_title ?? 'Lulusan Baru / Fresh Graduate' }}</div>
        
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
            <div class="item-subtitle">{{ $exp->company ?? '' }} @if(!empty($exp->location)) | {{ $exp->location }} @endif</div>
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
            <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
        </div>
        @endforeach
        @endif

        @if(count($hard_skills) > 0)
        <div class="right-header">Keahlian Teknis</div>
        <div style="margin-bottom: 14pt;">
            @foreach($hard_skills as $skill)
            <div style="margin-bottom: 6pt;">
                <div style="font-size: 8.5pt; font-weight: bold; color: #111827; margin-bottom: 2pt;">{{ $skill->name ?? '' }} <span style="float: right; color: #D4AF37; font-size: 8pt;">{{ $skill->level ?? '' }}%</span></div>
                <div style="width: 100%; background-color: #f3f4f6; height: 3.5pt; border-radius: 2pt;">
                    <div style="width: {{ $skill->level ?? '' }}%; background-color: #D4AF37; height: 3.5pt; border-radius: 2pt;"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        @if(count($soft_skills) > 0)
        <div class="right-header">Keahlian Interpersonal</div>
        <ul class="skill-list">
            @foreach($soft_skills as $skill)
            <li>{{ $skill->name ?? '' }}</li>
            @endforeach
        </ul>
        @endif

        @if(!$hasPage2)
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
    </div>

    <!-- PAGE 2: FULL WIDTH PURE WHITE -->
    @if($hasPage2)
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
            <div class="item-subtitle">{{ $int->company ?? '' }} @if(!empty($int->location)) | {{ $int->location }} @endif</div>
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

        <!-- SERTIFIKASI & PRESTASI -->
        @if(count($certificates) > 0)
        <div class="right-header">Sertifikasi & Prestasi</div>
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
    @endif
</body>
</html>
