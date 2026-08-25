<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Global Tech & Corporate ATS Resume</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #fff; }
        body {
            padding: 36px 48px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            font-size: 10pt;
            color: #111827;
            line-height: 1.45;
            box-sizing: border-box;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .header {
            text-align: center;
            margin-bottom: 16px;
            border-bottom: 2px solid #111827;
            padding-bottom: 12px;
        }
        .name {
            font-size: 22pt;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 1.5px;
            color: #0f172a;
        }
        .job-title {
            font-size: 11pt;
            font-weight: bold;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .contact-info {
            font-size: 9pt;
            color: #4b5563;
        }
        .contact-info span {
            margin: 0 5px;
        }
        .section-title {
            font-size: 11pt;
            font-weight: 800;
            text-transform: uppercase;
            border-bottom: 1.5px solid #1f2937;
            padding-bottom: 2px;
            margin-top: 14px;
            margin-bottom: 8px;
            letter-spacing: 1px;
            color: #0f172a;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-weight: bold;
            font-size: 10.5pt;
            color: #111827;
        }
        .item-date {
            text-align: right;
            font-size: 9.5pt;
            color: #4b5563;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 9.5pt;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 9.5pt;
            color: #374151;
            margin-bottom: 10px;
            text-align: justify;
        }
        .skills-table td {
            padding: 3px 0;
            vertical-align: top;
            font-size: 9.5pt;
        }
        .skills-category {
            font-weight: bold;
            width: 140px;
            color: #111827;
        }
        ul {
            margin: 3px 0 8px 18px;
            padding: 0;
        }
        li {
            margin-bottom: 3px;
            color: #374151;
            font-size: 9.5pt;
        }
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
    <div class="header">
        <div class="name">{{ $cv['name'] ?? 'ALEXANDER WRIGHT' }}</div>
        @if(!empty($cv['job_title']))
            <div class="job-title">{{ $cv['job_title'] }}</div>
        @endif
        <div class="contact-info">
            {{ $cv['email'] ?? 'alex.wright@example.com' }}
            @if(!empty($cv['phone'])) <span>•</span> {{ $cv['phone'] }} @endif
            @if(!empty($cv['address'] ?? $cv['location'])) <span>•</span> {{ $cv['address'] ?? $cv['location'] }} @endif
            @if(!empty($cv['linkedin'])) <span>•</span> {{ $cv['linkedin'] }} @endif
            @if(!empty($cv['website'])) <span>•</span> {{ $cv['website'] }} @endif
        </div>
    </div>

    <!-- PROFESSIONAL SUMMARY -->
    @if(!empty($cv['profile']))
        <div class="section-title">Professional Summary</div>
        <div class="item-desc">
            {{ $cv['profile'] }}
        </div>
    @endif

    <!-- WORK EXPERIENCE -->
    @if(!empty($experiences))
        <div class="section-title">Work Experience</div>
        @foreach($experiences as $exp)
            <table class="item-title-row">
                <tr>
                    <td class="item-title">{{ $exp['position'] ?? '' }}</td>
                    <td class="item-date">
                        {{ $exp['start_year'] ?? '' }} - {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                    </td>
                </tr>
            </table>
            <div class="item-subtitle">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ' | ' . $exp['location'] : '' }}</div>
            @if(!empty($exp['description']))
                <div class="item-desc">
                    {!! nl2br(e($exp['description'])) !!}
                </div>
            @endif
        @endforeach
    @endif

    <!-- KEY PROJECTS -->
    @if(!empty($projects))
        <div class="section-title">Key Projects & Engineering Portfolio</div>
        @foreach($projects as $prj)
            <table class="item-title-row">
                <tr>
                    <td class="item-title">{{ $prj['name'] ?? '' }}</td>
                    <td class="item-date">{{ $prj['year'] ?? $prj['link'] ?? '' }}</td>
                </tr>
            </table>
            @php
                $prjSub = array_filter([$prj['role'] ?? '', $prj['technologies'] ?? '', (!empty($prj['year']) ? $prj['link'] ?? '' : '')]);
            @endphp
            @if(!empty($prjSub))
                <div class="item-subtitle">{{ implode(' | ', $prjSub) }}</div>
            @endif
            @if(!empty($prj['description']))
                <div class="item-desc">
                    {{ $prj['description'] }}
                </div>
            @endif
        @endforeach
    @endif

    <!-- EDUCATION -->
    @if(!empty($educations))
        <div class="section-title">Education</div>
        @foreach($educations as $edu)
            <table class="item-title-row">
                <tr>
                    <td class="item-title">{{ $edu['institution'] ?? '' }}</td>
                    <td class="item-date">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? '' }}</td>
                </tr>
            </table>
            <div class="item-subtitle">
                {{ $edu['degree'] ?? '' }}{{ !empty($edu['major'] ?? $edu['field'] ?? null) ? ' in ' . ($edu['major'] ?? $edu['field']) : '' }}
            </div>
            @if(!empty($edu['description']))
                <div class="item-desc">{{ $edu['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- SKILLS & CORE COMPETENCIES -->
    @if(!empty($skills))
        <div class="section-title">Skills & Core Competencies</div>
        <table class="skills-table">
            <tr>
                <td class="skills-category">Technical Skills:</td>
                <td>
                    @php
                        $skillNames = array_map(function($s) { return is_array($s) ? ($s['name'] ?? '') : (string)$s; }, $skills);
                    @endphp
                    {{ implode(' • ', array_filter($skillNames)) }}
                </td>
            </tr>
        </table>
    @endif

    <!-- CERTIFICATIONS -->
    @if(!empty($certificates))
        <div class="section-title">Certifications & Honors</div>
        @foreach($certificates as $cert)
            <table class="item-title-row">
                <tr>
                    <td class="item-title">{{ $cert['name'] ?? '' }}</td>
                    <td class="item-date">{{ $cert['year'] ?? '' }}</td>
                </tr>
            </table>
            @if(!empty($cert['issuer']))
                <div class="item-subtitle">{{ $cert['issuer'] }}</div>
            @endif
        @endforeach
    @endif

</body>
</html>
