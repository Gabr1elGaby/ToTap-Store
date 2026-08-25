<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Academic, Research & Fellowship CV</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #ffffff; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 40px 50px;
            font-size: 10.5pt;
            color: #000000;
            line-height: 1.45;
            background-color: #fff;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }

        .name {
            font-size: 22pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }
        .contact {
            text-align: center;
            font-size: 9.5pt;
            margin-bottom: 18px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .contact span { margin: 0 5px; }

        .sec-head {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #000;
            margin-top: 14px;
            margin-bottom: 8px;
            padding-bottom: 2px;
        }

        .row-table { width: 100%; margin-bottom: 2px; }
        .item-main { font-weight: bold; }
        .item-year { text-align: right; white-space: nowrap; font-size: 10pt; }
        .item-sub { font-style: italic; margin-bottom: 3px; font-size: 10pt; }
        .item-body { font-size: 9.5pt; margin-bottom: 10px; text-align: justify; }
    </style>
</head>
<body>

    @php
        $cv = (array)($userData['cv'] ?? ($cv ?? []));
        $normalizeList = fn($list) => array_map(fn($i) => (array)$i, (array)($list ?? []));
        $educations = $normalizeList($userData['educations'] ?? ($educations ?? []));
        $experiences = $normalizeList($userData['experiences'] ?? ($experiences ?? []));
        $skills = $normalizeList($userData['skills'] ?? ($skills ?? []));
        $projects = $normalizeList($userData['projects'] ?? ($projects ?? []));
        $certificates = $normalizeList($userData['certificates'] ?? ($certificates ?? []));
    @endphp

    <!-- HEADER -->
    <div class="name">{{ $cv['name'] ?? 'PROF. ELEANOR VANCE, Ph.D.' }}</div>
    <div class="contact">
        {{ $cv['email'] ?? 'e.vance@university.edu' }}
        @if(!empty($cv['phone'])) <span>|</span> {{ $cv['phone'] }} @endif
        @if(!empty($cv['address'] ?? $cv['location'])) <span>|</span> {{ $cv['address'] ?? $cv['location'] }} @endif
        @if(!empty($cv['website'])) <span>|</span> {{ $cv['website'] }} @endif
    </div>

    <!-- RESEARCH INTERESTS -->
    @if(!empty($cv['profile']))
        <div class="sec-head">Research Interests & Scholarly Profile</div>
        <div class="item-body">
            {{ $cv['profile'] }}
        </div>
    @endif

    <!-- EDUCATION -->
    @if(!empty($educations))
        <div class="sec-head">Academic Appointments & Education</div>
        @foreach($educations as $edu)
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $edu['institution'] ?? '' }}</td>
                    <td class="item-year">{{ $edu['start_year'] ?? '' }} – {{ $edu['end_year'] ?? '' }}</td>
                </tr>
            </table>
            <div class="item-sub">{{ $edu['degree'] ?? '' }}{{ !empty($edu['major'] ?? $edu['field'] ?? null) ? ', ' . ($edu['major'] ?? $edu['field']) : '' }}</div>
            @if(!empty($edu['description']))
                <div class="item-body">{{ $edu['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- TEACHING & RESEARCH EXPERIENCE -->
    @if(!empty($experiences))
        <div class="sec-head">Research & Teaching Experience</div>
        @foreach($experiences as $exp)
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $exp['position'] ?? '' }}</td>
                    <td class="item-year">
                        {{ $exp['start_year'] ?? '' }} – {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                    </td>
                </tr>
            </table>
            <div class="item-sub">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ', ' . $exp['location'] : '' }}</div>
            @if(!empty($exp['description']))
                <div class="item-body">
                    {!! nl2br(e($exp['description'])) !!}
                </div>
            @endif
        @endforeach
    @endif

    <!-- PUBLICATIONS & RESEARCH PROJECTS -->
    @if(!empty($projects))
        <div class="sec-head">Selected Publications & Research Grants</div>
        @foreach($projects as $prj)
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $prj['name'] ?? '' }}</td>
                    <td class="item-year">{{ $prj['year'] ?? $prj['link'] ?? '' }}</td>
                </tr>
            </table>
            @php
                $prjSub = array_filter([$prj['role'] ?? '', $prj['technologies'] ?? '', (!empty($prj['year']) ? $prj['link'] ?? '' : '')]);
            @endphp
            @if(!empty($prjSub))
                <div class="item-sub">{{ implode(' | ', $prjSub) }}</div>
            @endif
            @if(!empty($prj['description']))
                <div class="item-body">{{ $prj['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- HONORS & AWARDS -->
    @if(!empty($certificates))
        <div class="sec-head">Fellowships, Honors & Grants</div>
        @foreach($certificates as $cert)
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $cert['name'] ?? '' }}</td>
                    <td class="item-year">{{ $cert['year'] ?? '' }}</td>
                </tr>
            </table>
            @if(!empty($cert['issuer']))
                <div class="item-sub">{{ $cert['issuer'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- METHODOLOGIES & LANGUAGES -->
    @if(!empty($skills))
        <div class="sec-head">Methodologies & Languages</div>
        <div class="item-body" style="margin-top: 4px;">
            @php
                $skillNames = array_map(function($s) { return is_array($s) ? ($s['name'] ?? '') : (string)$s; }, $skills);
            @endphp
            {{ implode('; ', array_filter($skillNames)) }}
        </div>
    @endif

</body>
</html>
