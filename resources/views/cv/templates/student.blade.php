<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Mahasiswa / Student</title>
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
            color: #334155;
            line-height: 1.4;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .main-table { border-collapse: collapse; table-layout: fixed; width: 100%; height: 100%; min-height: 1123px; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        
        /* Purple/Magenta Theme */
        .left-col {
            width: 33%;
            background-color: #831843; /* Magenta 900 */
            color: #fdf2f8; /* Magenta 50 */
            vertical-align: top;
            padding: 40px 25px;
        }
        .right-col {
            width: 67%;
            background-color: #ffffff;
            padding: 40px 35px;
            vertical-align: top;
        }

        /* Photo Area */
        .photo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        .photo {
            width: 120px; height: 120px;
            border-radius: 50%;
            border: 4px solid #fbcfe8;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        
        .name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
            line-height: 1.25;
            color: #ffffff;
        }
        .job-title {
            font-size: 9.5pt;
            text-align: center;
            text-transform: uppercase;
            margin-bottom: 20px;
            color: #fbcfe8;
            letter-spacing: 1px;
            line-height: 1.3;
        }

        /* Left Content */
        .left-header {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #be185d;
            padding-bottom: 5px;
            letter-spacing: 1px;
        }
        .contact-list { padding: 0; margin: 0; list-style: none; }
        .contact-list li { margin-bottom: 12px; font-size: 10pt; }
        .contact-icon {
            color: #f9a8d4;
            font-size: 12pt;
            margin-right: 8px;
            display: inline-block;
            width: 15px; text-align: center;
        }
        
        .profile-text { text-align: justify; font-size: 10pt; margin-bottom: 20px; }

        /* Right Content */
        .right-header {
            background-color: #831843;
            color: #ffffff;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            padding: 5px 15px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 12px;
            margin-top: 20px;
            letter-spacing: 1px;
        }
        .right-header:first-child { margin-top: 0; }
        
        .item { margin-bottom: 18px; }
        .item-title-row { width: 100%; margin-bottom: 2px; }
        .item-title { font-weight: bold; font-size: 12pt; color: #831843; }
        .item-date { text-align: right; font-size: 10pt; color: #4b5563;  white-space: nowrap; }
        .item-subtitle { font-size: 11pt; color: #475569; font-weight: bold; margin-bottom: 4px; }
        .item-desc { font-size: 10pt; color: #4b5563; text-align: justify; }

        .skill-list { padding-left: 15px; margin: 0; }
        .skill-list li { margin-bottom: 4px; font-size: 9.5pt; color: #334155; }
        .skill-list li::before {
            content: "■"; color: #831843; font-size: 8pt; margin-right: 8px;
            display: inline-block; margin-left: -15px;
        }

    </style>
</head>
<body>
    @php
        $cvRaw = $userData['cv'] ?? ($cv ?? []);
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

        $getCol = function($key) use ($userData) {
            if (isset($userData[$key]) && (is_array($userData[$key]) || $userData[$key] instanceof \Illuminate\Support\Collection)) {
                return collect($userData[$key])->map(fn($i) => (object)$i);
            }
            if (isset($userData['cv'][$key]) && is_array($userData['cv'][$key])) {
                return collect($userData['cv'][$key])->map(fn($i) => (object)$i);
            }
            if (isset($userData['cv']) && is_object($userData['cv']) && isset($userData['cv']->$key)) {
                return collect($userData['cv']->$key)->map(fn($i) => (object)$i);
            }
            return collect([]);
        };

        $educations    = $getCol('educations');
        $experiences   = $getCol('experiences');
        $internships   = $getCol('internships');
        $organizations = $getCol('organizations');
        $volunteers    = $getCol('volunteers');
        $org_and_vol   = $organizations->concat($volunteers);
        $projects      = $getCol('projects');
        $certificates  = $getCol('certificates');
        $skills        = $getCol('skills');

        $hard_skills = $skills->slice(0, 5)->all();
        $soft_skills = $skills->slice(5)->all();
    @endphp

    <table class="main-table">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                @if(!empty($data->photo))
                <div class="photo-wrapper">
                    <img src="{{ $data->photo }}" class="photo">
                </div>
                @else
                <div style="height: 50px;"></div>
                @endif

                <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
                <div class="job-title">{{ $data->job_title ?? 'Mahasiswa' }}</div>

                @if(!empty($data->profile))
                <div class="left-header">Profil</div>
                <div class="profile-text">
                    {!! nl2br(e($data->profile)) !!}
                </div>
                @endif

                <div class="left-header">Kontak</div>
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
                
                
            </td>

            <!-- RIGHT COLUMN -->
            <td class="right-col">
                
                @if(count($educations) > 0)
                <div><span class="right-header">Pendidikan</span></div>
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
                </div>
                @endforeach
                @endif

                @if(count($org_and_vol) > 0)
                <div><span class="right-header">Pengalaman Organisasi & Relawan</span></div>
                @foreach($org_and_vol as $org)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $org->role ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $org->period ?? $org->year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $org->organization_name ?? $org->name ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($internships) > 0)
                <div><span class="right-header">Pengalaman Magang</span></div>
                @foreach($internships as $int)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $int->position ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $int->company ?? '' }}{{ !empty($int->location) ? ' | ' . $int->location : '' }}</div>
                    <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
                </div>
                @endforeach
                @endif

                @if(count($projects) > 0)
                <div><span class="right-header">Proyek & Portofolio</span></div>
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

                @if(count($hard_skills) > 0)
                <div><span class="right-header">Keahlian Teknis</span></div>
                <div style="padding-left: 15px; margin-bottom: 20px;">
                    @foreach($hard_skills as $skill)
                    @php
                        $lvl = is_numeric($skill->level ?? null) ? (int)$skill->level : 90;
                    @endphp
                    <div style="margin-bottom: 12px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 4px;">
                            <tr>
                                <td style="font-size: 10pt; color: #334155; font-weight: bold;">{{ $skill->name ?? '' }}</td>
                                <td align="right" style="color: #831843; font-size: 9pt; font-weight: bold;">{{ $lvl }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #f1f5f9; height: 6px; border-radius: 3px;">
                            <div style="width: {{ $lvl }}%; background-color: #831843; height: 100%; border-radius: 3px;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
                
                @if(count($soft_skills) > 0)
                <div><span class="right-header">Keahlian Interpersonal</span></div>
                <ul class="skill-list" style="padding-left: 30px; margin-bottom: 20px;">
                    @foreach($soft_skills as $skill)
                    <li>{{ $skill->name ?? '' }}</li>
                    @endforeach
                </ul>
                @endif

                @if(count($certificates) > 0)
                <div style="margin-top: 20px;"><span class="right-header">Sertifikasi & Prestasi</span></div>
                @foreach($certificates as $cert)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $cert->name ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $cert->year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-desc">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
                </div>
                @endforeach
                @endif

            </td>
        </tr>
    </table>
</body>
</html>
