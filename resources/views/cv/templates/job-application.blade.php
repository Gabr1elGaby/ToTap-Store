<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Lamaran Kerja</title>
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
            color: #1e293b;
            line-height: 1.5;
        }
        
        .sidebar {
            position: absolute;
            left: 0;
            top: 0;
            width: 34%;
            min-height: 100%;
            background-color: #1e293b; /* Navy */
            color: #e2e8f0;
            padding: 40px 25px;
            box-sizing: border-box;
        }
        .content {
            margin-left: 34%;
            width: 66%;
            background-color: #ffffff;
            padding: 40px 35px;
            box-sizing: border-box;
        }

        /* Photo Area */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 30px;
        }
        .photo {
            width: 130px; height: 130px;
            border: 6px solid #f59e0b; /* Amber/Orange */
            object-fit: cover;
            background-color: #fff;
            display: block;
            margin: 0 auto;
        }

        /* Left Col Headers */
        .left-header {
            background-color: #f59e0b;
            color: #1e293b;
            font-size: 11pt;
            font-weight: bold;
            padding: 5px 15px;
            margin-bottom: 15px;
            margin-top: 25px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-block;
        }
        
        .contact-item {
            margin-bottom: 12px;
            font-size: 9pt;
            word-wrap: break-word;
            word-break: break-all;
        }
        .contact-label {
            font-weight: bold;
            color: #f59e0b;
            text-transform: uppercase;
            font-size: 7.5pt;
            display: block;
            margin-bottom: 2px;
        }
        .contact-value { color: #f8fafc; }

        /* Right Col Elements */
        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .job-title {
            font-size: 11pt;
            color: #f59e0b;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 25px;
            letter-spacing: 1px;
        }
        .right-header {
            font-size: 12.5pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            border-left: 5px solid #f59e0b;
            padding-left: 10px;
            margin-bottom: 15px;
            margin-top: 25px;
            letter-spacing: 0.5px;
        }
        
        .profile-text { text-align: justify; margin-bottom: 25px; }

        .item { margin-bottom: 18px; page-break-inside: avoid; }
        .item-title-row { width: 100%; margin-bottom: 2px; }
        .item-title { font-weight: bold; font-size: 11pt; color: #1e293b; }
        .item-date { text-align: right; font-size: 9.5pt; color: #64748b; font-weight: bold; white-space: nowrap; }
        .item-subtitle { font-size: 10pt; color: #475569; font-weight: bold; margin-bottom: 4px; }
        .item-desc { font-size: 9.5pt; color: #334155; text-align: justify; }

        .skill-list { list-style: none; padding: 0; margin: 0; }
        .skill-list li { margin-bottom: 6px; font-size: 9.5pt; color: #f8fafc; }
        .skill-list li::before {
            content: "■";
            color: #f59e0b;
            margin-right: 8px;
            font-size: 8pt;
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
        <div style="margin-bottom: 20px;">
            @foreach($hard_skills as $skill)
            <div style="margin-bottom: 10px;">
                <div style="font-size: 9.5pt; margin-bottom: 3px; color: #f8fafc;">{{ $skill->name ?? '' }}</div>
                <div style="width: 100%; background-color: #334155; height: 5px; border-radius: 3px;">
                    <div style="width: {{ $skill->level }}%; background-color: #f59e0b; height: 5px; border-radius: 3px;"></div>
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
