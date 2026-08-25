<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Global Executive & Leadership CV</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #ffffff; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Georgia', 'Times New Roman', serif;
            margin: 0;
            padding: 40px 50px;
            font-size: 10pt;
            color: #1f2937;
            line-height: 1.5;
            background-color: #fff;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }

        .header {
            text-align: center;
            border-bottom: 2.5px double #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 18px;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: #1e3a8a;
            margin: 0 0 4px 0;
        }
        .job-title {
            font-size: 11pt;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-weight: 700;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .contact-info {
            font-size: 9pt;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #4b5563;
        }
        .contact-info span { margin: 0 6px; }

        .sec-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #1e3a8a;
            border-bottom: 1px solid #1e3a8a;
            padding-bottom: 2px;
            margin-top: 16px;
            margin-bottom: 10px;
        }

        .item-title { font-weight: bold; font-size: 10.5pt; color: #111827; }
        .item-date { text-align: right; font-size: 9.5pt; font-family: 'Helvetica', 'Arial', sans-serif; color: #6b7280; }
        .item-comp { font-style: italic; font-size: 10pt; color: #4b5563; margin-bottom: 4px; }
        .item-desc { font-size: 9.5pt; color: #374151; margin-bottom: 12px; text-align: justify; }

        .skills-grid {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 9pt;
            margin-bottom: 10px;
        }
        .skills-grid td { padding: 4px 6px; }
    </style>
</head>
<body>

    @php
        $cv = $userData['cv'] ?? [];
        $educations = $userData['educations'] ?? [];
        $experiences = $userData['experiences'] ?? [];
        $skills = $userData['skills'] ?? [];
        $projects = $userData['projects'] ?? [];
        $certificates = $userData['certificates'] ?? [];
    @endphp

    <!-- HEADER -->
    <div class="header">
        <div class="name">{{ $cv['name'] ?? 'DAVID STERLING, MBA' }}</div>
        <div class="job-title">{{ $cv['job_title'] ?? 'Vice President of Global Engineering & Operations' }}</div>
        <div class="contact-info">
            {{ $cv['email'] ?? 'david.sterling@executive.com' }}
            @if(!empty($cv['phone'])) <span>|</span> {{ $cv['phone'] }} @endif
            @if(!empty($cv['address'] ?? $cv['location'])) <span>|</span> {{ $cv['address'] ?? $cv['location'] }} @endif
            @if(!empty($cv['linkedin'])) <span>|</span> {{ $cv['linkedin'] }} @endif
        </div>
    </div>

    <!-- EXECUTIVE PROFILE -->
    @if(!empty($cv['profile']))
        <div class="sec-title">Executive Profile & Vision</div>
        <div class="item-desc">
            {{ $cv['profile'] }}
        </div>
    @endif

    <!-- CAREER HISTORY -->
    @if(!empty($experiences))
        <div class="sec-title">Executive Leadership & Experience</div>
        @foreach($experiences as $exp)
            <table style="width: 100%;">
                <tr>
                    <td class="item-title">{{ $exp['position'] ?? '' }}</td>
                    <td class="item-date">
                        {{ $exp['start_year'] ?? '' }} – {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                    </td>
                </tr>
            </table>
            <div class="item-comp">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ', ' . $exp['location'] : '' }}</div>
            @if(!empty($exp['description']))
                <div class="item-desc">
                    {!! nl2br(e($exp['description'])) !!}
                </div>
            @endif
        @endforeach
    @endif

    <!-- CORE COMPETENCIES -->
    @if(!empty($skills))
        <div class="sec-title">Core Competencies & Strategic Capabilities</div>
        <table class="skills-grid">
            <tr>
                @php
                    $skillNames = array_map(function($s) { return is_array($s) ? ($s['name'] ?? '') : (string)$s; }, $skills);
                    $chunks = array_chunk(array_filter($skillNames), 3);
                @endphp
                @foreach($chunks as $chunk)
                    <tr>
                        @foreach($chunk as $sk)
                            <td>• <strong>{{ $sk }}</strong></td>
                        @endforeach
                    </tr>
                @endforeach
            </tr>
        </table>
    @endif

    <!-- EDUCATION & CREDENTIALS -->
    @if(!empty($educations))
        <div class="sec-title">Education & Academic Background</div>
        @foreach($educations as $edu)
            <table style="width: 100%;">
                <tr>
                    <td class="item-title">{{ $edu['institution'] ?? '' }}</td>
                    <td class="item-date">{{ $edu['start_year'] ?? '' }} – {{ $edu['end_year'] ?? '' }}</td>
                </tr>
            </table>
            <div class="item-comp">{{ $edu['degree'] ?? '' }}{{ !empty($edu['major'] ?? $edu['field'] ?? null) ? ' in ' . ($edu['major'] ?? $edu['field']) : '' }}</div>
            @if(!empty($edu['description']))
                <div class="item-desc">{{ $edu['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- BOARD MEMBERSHIPS & CERTIFICATIONS -->
    @if(!empty($certificates))
        <div class="sec-title">Executive Certifications & Advisory Roles</div>
        @foreach($certificates as $cert)
            <table style="width: 100%;">
                <tr>
                    <td class="item-title">{{ $cert['name'] ?? '' }}</td>
                    <td class="item-date">{{ $cert['year'] ?? '' }}</td>
                </tr>
            </table>
            @if(!empty($cert['issuer']))
                <div class="item-comp">{{ $cert['issuer'] }}</div>
            @endif
        @endforeach
    @endif

</body>
</html>
