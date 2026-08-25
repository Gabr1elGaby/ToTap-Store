<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV ATS Friendly</title>
    <style>
        @page { margin: 0px; }
        html, body { margin: 0; padding: 0; background-color: #fff; }
        body {
            padding: 40px 50px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            font-size: 10.5pt;
            color: #000000;
            line-height: 1.45;
            box-sizing: border-box;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            letter-spacing: 1px;
            color: #111827;
        }
        .contact-info {
            font-size: 10pt;
            color: #374151;
            line-height: 1.5;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            margin-top: 20px;
            margin-bottom: 12px;
            letter-spacing: 1px;
            color: #000000;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-weight: bold;
            font-size: 11pt;
            color: #111827;
        }
        .item-date {
            text-align: right;
            font-size: 10pt;
            white-space: nowrap;
            color: #4b5563;
        }
        .item-subtitle {
            font-style: italic;
            font-size: 10pt;
            color: #374151;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 10pt;
            color: #374151;
            text-align: justify;
            margin-bottom: 14px;
            line-height: 1.45;
        }
        .skills-list {
            margin: 0;
            padding-left: 20px;
            font-size: 10pt;
        }
        .skills-list li {
            margin-bottom: 3px;
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
    @endphp

    <!-- HEADER / DATA PRIBADI & KONTAK -->
    <div class="header">
        <div class="name">{{ $data->name ?? 'RADITYA PRATAMA, S.Kom.' }}</div>
        <div class="contact-info">
            @if($getVal($data, 'address', 'location') !== '') {{ $getVal($data, 'address', 'location') }} &nbsp;|&nbsp; @endif
            @if(!empty($data->phone)) {{ $data->phone }} &nbsp;|&nbsp; @endif
            @if(!empty($data->email)) {{ $data->email }} @endif
            @if(!empty($data->linkedin)) &nbsp;|&nbsp; {{ $data->linkedin }} @endif
            @if(!empty($data->website)) &nbsp;|&nbsp; {{ $data->website }} @endif
        </div>
    </div>

    <!-- RINGKASAN PROFIL -->
    @if(!empty($data->profile))
    <div class="section-title">RINGKASAN PROFIL</div>
    <div class="item-desc">
        {!! nl2br(e($data->profile)) !!}
    </div>
    @endif

    <!-- PENGALAMAN KERJA -->
    @if(count($experiences) > 0)
    <div class="section-title">PENGALAMAN KERJA</div>
    @foreach($experiences as $exp)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 70%;">{{ $exp->position ?? '' }}</td>
                <td class="item-date" style="width: 30%;">{{ $exp->start_year ?? '' }} - {{ isset($exp->is_current) && $exp->is_current ? 'Sekarang' : ($exp->end_year ?? '') }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $exp->company ?? '' }} @if(!empty($exp->location)) | {{ $exp->location ?? '' }} @endif</div>
        <div class="item-desc">{!! nl2br(e($exp->description)) !!}</div>
    </div>
    @endforeach
    @endif

    <!-- PENGALAMAN MAGANG -->
    @if(count($internships) > 0)
    <div class="section-title">PENGALAMAN MAGANG</div>
    @foreach($internships as $int)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 70%;">{{ $int->position ?? '' }}</td>
                <td class="item-date" style="width: 30%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $int->company ?? '' }}{{ !empty($int->location) ? ' | ' . $int->location : '' }}</div>
        <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
    </div>
    @endforeach
    @endif

    <!-- RIWAYAT PENDIDIKAN -->
    @if(count($educations) > 0)
    <div class="section-title">RIWAYAT PENDIDIKAN</div>
    @foreach($educations as $edu)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 70%;">{{ $edu->institution ?? '' }}</td>
                <td class="item-date" style="width: 30%;">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</td>
            </tr>
        </table>
        @php
            $deg = $edu->degree ?? '';
            $maj = $getVal($edu, 'major', 'field');
        @endphp
        <div class="item-subtitle">{{ $deg }}{{ $maj !== '' ? ($deg !== '' ? ', ' : '') . $maj : '' }}</div>
        @if(!empty($edu->description))
        <div class="item-desc">{{ $edu->description }}</div>
        @endif
    </div>
    @endforeach
    @endif

    <!-- PENGALAMAN ORGANISASI -->
    @if(count($organizations) > 0)
    <div class="section-title">PENGALAMAN ORGANISASI</div>
    @foreach($organizations as $org)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 70%;">{{ $org->role ?? '' }}</td>
                <td class="item-date" style="width: 30%;">{{ $org->period ?? $org->year ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $org->organization_name ?? $org->name ?? '' }}</div>
        <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
    </div>
    @endforeach
    @endif
    
    <!-- PROYEK & PORTOFOLIO -->
    @if(count($projects) > 0)
    <div class="section-title">PROYEK & PORTOFOLIO</div>
    @foreach($projects as $proj)
    <div>
        <table class="item-title-row">
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
        @if(!empty($proj->description))
        <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
        @endif
    </div>
    @endforeach
    @endif

    <!-- SERTIFIKASI & PELATIHAN -->
    @if(count($certificates) > 0)
    <div class="section-title">SERTIFIKASI & PELATIHAN</div>
    @foreach($certificates as $cert)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 70%;">{{ $cert->name ?? '' }}</td>
                <td class="item-date" style="width: 30%;">{{ $cert->year ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
    </div>
    @endforeach
    @endif

    <!-- KEAHLIAN & KETERAMPILAN -->
    @if(count($skills) > 0)
    <div class="section-title">KEAHLIAN & KETERAMPILAN</div>
    <div class="item-desc">
        {{ collect($skills)->pluck('name')->join(' • ') }}
    </div>
    @endif

</body>
</html>
