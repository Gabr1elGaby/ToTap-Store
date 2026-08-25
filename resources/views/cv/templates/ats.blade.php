<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV ATS Friendly</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #fff; }
        body {
            padding: 40px 50px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            font-size: 11pt;
            color: #000000;
            line-height: 1.4;
            box-sizing: border-box;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }
        .contact-info {
            font-size: 10pt;
            color: #333333;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-weight: bold;
            font-size: 11pt;
        }
        .item-date {
            text-align: right;
            font-size: 10.5pt;
         white-space: nowrap; }
        .item-subtitle {
            font-style: italic;
            font-size: 10.5pt;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 10.5pt;
            text-align: justify;
            margin-bottom: 12px;
        }
        .skills-list {
            margin: 0;
            padding-left: 20px;
            font-size: 10.5pt;
        }
        .skills-list li {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    @php
        $data = isset($userData['cv']) && is_array($userData['cv']) ? (object)$userData['cv'] : (isset($userData['cv']) ? $userData['cv'] : (object)[]);
        $educations = isset($userData['cv']['educations']) && is_array($userData['cv']['educations']) ? collect($userData['cv']['educations'])->map(fn($i) => (object)$i) : [];
        $experiences = isset($userData['cv']['experiences']) && is_array($userData['cv']['experiences']) ? collect($userData['cv']['experiences'])->map(fn($i) => (object)$i) : [];
        $projects = isset($userData['cv']['projects']) && is_array($userData['cv']['projects']) ? collect($userData['cv']['projects'])->map(fn($i) => (object)$i) : [];
        $internships = isset($userData['cv']['internships']) && is_array($userData['cv']['internships']) ? collect($userData['cv']['internships'])->map(fn($i) => (object)$i) : [];
        $organizations = isset($userData['cv']['organizations']) && is_array($userData['cv']['organizations']) ? collect($userData['cv']['organizations'])->map(fn($i) => (object)$i) : [];
        $certificates = isset($userData['cv']['certificates']) && is_array($userData['cv']['certificates']) ? collect($userData['cv']['certificates'])->map(fn($i) => (object)$i) : [];
        $skills = isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? collect($userData['cv']['skills'])->map(fn($i) => (object)$i) : [];
        
    @endphp

    <div class="header">
        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="contact-info">
            @if(!empty($data->address)) {{ $data->address ?? '' }} &nbsp;|&nbsp; @endif
            @if(!empty($data->phone)) {{ $data->phone ?? '' }} &nbsp;|&nbsp; @endif
    </style>
</head>
<body>
    @php
        $data = isset($userData['cv']) && is_array($userData['cv']) ? (object)$userData['cv'] : (isset($userData['cv']) ? $userData['cv'] : (object)[]);
        $educations = isset($userData['cv']['educations']) && is_array($userData['cv']['educations']) ? collect($userData['cv']['educations'])->map(fn($i) => (object)$i) : [];
        $experiences = isset($userData['cv']['experiences']) && is_array($userData['cv']['experiences']) ? collect($userData['cv']['experiences'])->map(fn($i) => (object)$i) : [];
        $projects = isset($userData['cv']['projects']) && is_array($userData['cv']['projects']) ? collect($userData['cv']['projects'])->map(fn($i) => (object)$i) : [];
        $internships = isset($userData['cv']['internships']) && is_array($userData['cv']['internships']) ? collect($userData['cv']['internships'])->map(fn($i) => (object)$i) : [];
        $organizations = isset($userData['cv']['organizations']) && is_array($userData['cv']['organizations']) ? collect($userData['cv']['organizations'])->map(fn($i) => (object)$i) : [];
        $certificates = isset($userData['cv']['certificates']) && is_array($userData['cv']['certificates']) ? collect($userData['cv']['certificates'])->map(fn($i) => (object)$i) : [];
        $skills = isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? collect($userData['cv']['skills'])->map(fn($i) => (object)$i) : [];
        
    @endphp

    <div class="header">
        <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
        <div class="contact-info">
            @if(!empty($data->address)) {{ $data->address ?? '' }} &nbsp;|&nbsp; @endif
            @if(!empty($data->phone)) {{ $data->phone ?? '' }} &nbsp;|&nbsp; @endif
            @if(!empty($data->email)) {{ $data->email ?? '' }} &nbsp;|&nbsp; @endif
            @if(!empty($data->linkedin)) {{ $data->linkedin ?? '' }} &nbsp;|&nbsp; @endif
            @if(!empty($data->website)) {{ $data->website ?? '' }} @endif
        </div>
    </div>

    @if(!empty($data->profile))
    <div class="section-title">RINGKASAN PROFIL</div>
    <div class="item-desc">
        {!! nl2br(e($data->profile)) !!}
    </div>
    @endif

    @if(count($experiences) > 0)
    <div class="section-title">PENGALAMAN KERJA</div>
    @foreach($experiences as $exp)
    <div>
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

    @if(count($internships) > 0)
    <div class="section-title">PENGALAMAN MAGANG</div>
    @foreach($internships as $int)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 75%;">{{ $int->position ?? '' }}</td>
                <td class="item-date" style="width: 25%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $int->company ?? '' }}</div>
        <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
    </div>
    @endforeach
    @endif

    @if(count($educations) > 0)
    <div class="section-title">RIWAYAT PENDIDIKAN</div>
    @foreach($educations as $edu)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 75%;">{{ $edu->institution ?? '' }}</td>
                <td class="item-date" style="width: 25%;">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $edu->degree ?? '' }}, {{ $edu->major ?? '' }}</div>
    </div>
    @endforeach
    @endif

    @if(count($organizations) > 0)
    <div class="section-title">PENGALAMAN ORGANISASI</div>
    @foreach($organizations as $org)
    <div>
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
    <div class="section-title">PROYEK & PORTOFOLIO</div>
    @foreach($projects as $proj)
    <div>
        <table class="item-title-row">
            <tr>
                <td class="item-title" style="width: 75%;">{{ $proj->name ?? '' }}</td>
                <td class="item-date" style="width: 25%;">{{ $proj->year ?? $proj->link ?? '' }}</td>
            </tr>
        </table>
        <div class="item-subtitle">{{ $proj->role ?? $proj->technologies ?? '' }}</div>
        <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
    </div>
    @endforeach
    @endif

    @if(count($certificates) > 0)
    <div class="section-title">SERTIFIKASI & PELATIHAN</div>
    @foreach($certificates as $cert)
    <div>
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

    @if(count($skills) > 0)
    <div class="section-title">KEAHLIAN & KETERAMPILAN</div>
    <div class="item-desc">
        {{ collect($skills)->pluck('name')->join(', ') }}
    </div>
    @endif

    
</body>
</html>
