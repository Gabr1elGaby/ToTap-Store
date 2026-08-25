<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Lamaran Kerja</title>
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
            color: #1e293b;
            line-height: 1.5;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .main-table { border-collapse: collapse; table-layout: fixed; width: 100%; height: 100%; min-height: 1123px; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        
        .left-col {
            width: 34%;
            background-color: #1e293b; /* Navy */
            color: #e2e8f0;
            vertical-align: top;
            padding: 40px 25px;
        }
        .right-col {
            width: 66%;
            background-color: #ffffff;
            padding: 40px 35px;
            vertical-align: top;
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
            text-align: center;
            letter-spacing: 1px;
        }
        
        .contact-list { padding: 0; margin: 0; list-style: none; }
        .contact-list li { margin-bottom: 12px; font-size: 9pt; }
        .contact-icon {
            background-color: #f59e0b;
            color: #1e293b;
            border-radius: 50%;
            display: inline-block;
            width: 20px; height: 20px;
            line-height: 20px;
            text-align: center;
            margin-right: 8px;
            font-size: 10pt;
            font-weight: bold;
        }

        /* Right Col Elements */
        .name-wrapper {
            background-color: #1e293b;
            color: #f59e0b;
            padding: 20px 25px;
            margin-bottom: 30px;
            margin-top: 10px;
            border-left: 10px solid #f59e0b;
        }
        .name {
            font-size: 19pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.5px;
            line-height: 1.2;
        }
        .job-title {
            font-size: 10pt;
            color: #e2e8f0;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .right-header {
            font-size: 13pt;
            font-weight: bold;
            color: #1e293b;
            text-transform: uppercase;
            border-bottom: 2px solid #f59e0b;
            padding-bottom: 4px;
            margin-bottom: 15px;
            margin-top: 25px;
            letter-spacing: 1px;
        }
        
        .profile-text { text-align: justify; margin-bottom: 25px; }

        .item { margin-bottom: 18px; }
        .item-title-row { width: 100%; margin-bottom: 3px; }
        .item-title { font-weight: bold; font-size: 11pt; color: #1e293b; }
        .item-date { text-align: right; font-size: 9.5pt; color: #f59e0b; font-weight: bold;  white-space: nowrap; }
        .item-subtitle { font-size: 10pt; color: #475569; margin-bottom: 4px; font-style: italic; }
        .item-desc { font-size: 9.5pt; color: #4b5563; text-align: justify; }

        .skill-list, .lang-list { padding-left: 20px; margin: 0; }
        .skill-list li, .lang-list li { margin-bottom: 4px; font-size: 9.5pt; color: #f8fafc; }

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
        $projects      = $getCol('projects');
        $certificates  = $getCol('certificates');
        $skills        = $getCol('skills');

        $hard_skills = $skills->filter(fn($s) => isset($s->level) && $s->level !== '' && $s->level !== null)->all();
        $soft_skills = $skills->filter(fn($s) => !isset($s->level) || $s->level === '' || $s->level === null)->all();
    @endphp

    <table class="main-table">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                <div class="photo-wrapper">
                    @if(!empty($data->photo))
                        <img src="{{ $data->photo }}" class="photo">
                    @else
                        <div class="photo" style="display:inline-block;"></div>
                    @endif
                </div>

                <div class="left-header">Kontak Pribadi</div>
                <ul class="contact-list">
                    @if(!empty($data->phone))
                    <li><span class="contact-icon">P</span> {{ $data->phone }}</li>
                    @endif
                    @if(!empty($data->email))
                    <li><span class="contact-icon">M</span> {{ $data->email }}</li>
                    @endif
                    @if($getVal($data, 'address', 'location') !== '')
                    <li><span class="contact-icon">A</span> {{ $getVal($data, 'address', 'location') }}</li>
                    @endif
                    @if(!empty($data->linkedin))
                    <li><span class="contact-icon">L</span> {{ $data->linkedin ?? '' }}</li>
                    @endif
                    @if(!empty($data->website))
                    <li><span class="contact-icon">W</span> {{ $data->website ?? '' }}</li>
                    @endif
                    @if(!empty($data->social_media))
                    <li><span class="contact-icon">S</span> {{ $data->social_media ?? '' }}</li>
                    @endif
                </ul>
                
                @if(count($skills) > 0)
                <div class="left-header">Keahlian</div>
                <div style="padding-left: 0; margin-bottom: 25px;">
                    @foreach($skills as $skill)
                    <div style="margin-bottom: 10px;">
                        @if(isset($skill->level) && is_numeric($skill->level))
                        <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                            <tr>
                                <td style="font-size: 9.5pt; color: #f8fafc;">{{ $skill->name ?? '' }}</td>
                                <td align="right" style="font-size: 8.5pt; color: #f59e0b;">{{ $skill->level }}%</td>
                            </tr>
                        </table>
                        <div style="width: 100%; background-color: #334155; height: 5px; border-radius: 3px;">
                            <div style="width: {{ $skill->level }}%; background-color: #f59e0b; height: 100%; border-radius: 3px;"></div>
                        </div>
                        @else
                        <div style="font-size: 9.5pt; color: #f8fafc; padding: 2px 0;">• {{ $skill->name ?? '' }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif
                
                
            </td>

            <!-- RIGHT COLUMN -->
            <td class="right-col">
                <div class="name-wrapper">
                    <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
                    <div class="job-title">{{ $data->job_title ?? '' }}</div>
                </div>
                
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
                </div>
                @endforeach
                @endif
                
                @if(count($organizations) > 0)
                <div class="right-header">Pengalaman Organisasi</div>
                @foreach($organizations as $org)
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
                    <div class="item-desc">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
                </div>
                @endforeach
                @endif

            </td>
        </tr>
    </table>
</body>
</html>
