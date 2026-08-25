<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Kreatif</title>
    <style>
        @page {
            margin: 0px;
        }
        html, body { height: 100%; margin: 0; padding: 0; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10pt;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.5;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .main-table { border-collapse: collapse; table-layout: fixed; width: 100%; height: 100%; min-height: 1123px; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .left-col {
            width: 35%;
            background-color: #1e293b;
            color: #cbd5e1;
            padding: 40px 25px;
            vertical-align: top;
        }
        .right-col {
            width: 65%;
            padding: 40px 35px;
            vertical-align: top;
            background-color: #ffffff;
        }
        
        /* Photo */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        .photo {
            width: 130px; height: 130px;
            border-radius: 50%;
            border: 4px solid #38bdf8; /* sky blue accent */
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }

        /* Identity */
        .name {
            font-size: 16pt;
            font-weight: bold;
            color: #ffffff;
            text-align: center;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            line-height: 1.25;
        }
        .job-title {
            font-size: 9.5pt;
            color: #38bdf8;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0 0 20px 0;
        }

        /* Left Section */
        .left-header {
            font-size: 12pt;
            font-weight: bold;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            border-bottom: 1px solid #334155;
            padding-bottom: 5px;
            margin-bottom: 15px;
            margin-top: 25px;
        }
        .contact-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .contact-list li {
            margin-bottom: 10px;
            font-size: 9pt;
            word-wrap: break-word;
        }
        .contact-icon {
            color: #38bdf8;
            font-size: 11pt;
            margin-right: 8px;
            width: 15px;
            display: inline-block;
            text-align: center;
        }
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            margin-bottom: 6px;
            font-size: 9.5pt;
        }
        .skill-list li::before {
            content: "○";
            color: #38bdf8;
            margin-right: 8px;
            font-weight: bold;
        }

        /* Right Section */
        .right-header {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 5px;
            margin-bottom: 20px;
            margin-top: 30px;
        }
        .right-header:first-child {
            margin-top: 0;
        }
        .profile-text {
            text-align: justify;
            margin-bottom: 30px;
        }

        /* Timeline Items */
        .item {
            margin-bottom: 20px;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #0f172a;
        }
        .item-date {
            font-size: 9.5pt;
            color: #38bdf8;
            font-weight: bold;
            text-align: right;
         white-space: nowrap; }
        .item-subtitle {
            font-size: 10pt;
            color: #475569;
            font-style: italic;
            margin-bottom: 5px;
        }
        .item-desc {
            font-size: 9.5pt;
            color: #334155;
            text-align: justify;
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

    <table class="main-table">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                
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
                    <li><span class="contact-icon">📞</span> {{ $data->phone }}</li>
                    @endif
                    @if(!empty($data->email))
                    <li><span class="contact-icon">✉</span> {{ $data->email }}</li>
                    @endif
                    @if($getVal($data, 'address', 'location') !== '')
                    <li><span class="contact-icon">📍</span> {{ $getVal($data, 'address', 'location') }}</li>
                    @endif
                    @if(!empty($data->linkedin))
                    <li><span class="contact-icon">in</span> {{ $data->linkedin ?? '' }}</li>
                    @endif
                    @if(!empty($data->website))
                    <li><span class="contact-icon">🌐</span> {{ $data->website ?? '' }}</li>
                    @endif
                    @if(!empty($data->social_media))
                    <li><span class="contact-icon">@</span> {{ $data->social_media ?? '' }}</li>
                    @endif
                </ul>

                @if(count($hard_skills) > 0)
                <div class="left-header">Keahlian Teknis</div>
                <div style="margin-bottom: 20px;">
                    @foreach($hard_skills as $skill)
                    <div style="margin-bottom: 8px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                            <tr>
                                <td style="font-size: 9.5pt;">{{ $skill->name ?? '' }}</td>
                                <td align="right" style="font-size: 9.5pt; color: #38bdf8;">{{ $skill->level ?? '' }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #334155; height: 4px; border-radius: 2px;">
                            <div style="width: {{ $skill->level ?? '' }}%; background-color: #38bdf8; height: 100%; border-radius: 2px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(count($soft_skills) > 0)
                <div class="left-header">Keahlian Interpersonal</div>
                <ul class="skill-list" style="margin-bottom: 20px;">
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

            <!-- RIGHT COLUMN -->
            <td class="right-col">
                
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
                    <table class="item-title-row">
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
                    <table class="item-title-row">
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
                    <table class="item-title-row">
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
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $cert->name ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $cert->year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
                </div>
                @endforeach
                @endif

            </td>
        </tr>
    </table>
</body>
</html>
