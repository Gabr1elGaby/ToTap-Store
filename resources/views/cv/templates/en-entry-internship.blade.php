<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>International Graduate & Internship Resume</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #ffffff; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 35px 45px;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.45;
            background-color: #fff;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }

        .header {
            text-align: center;
            border-bottom: 2px solid #059669;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .name {
            font-size: 22pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #065f46;
            margin: 0 0 4px 0;
        }
        .job-title {
            font-size: 10.5pt;
            font-weight: 700;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .contact {
            font-size: 9pt;
            color: #64748b;
        }
        .contact span { margin: 0 5px; }

        .sec-title {
            font-size: 10.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #065f46;
            border-bottom: 1.5px solid #059669;
            padding-bottom: 2px;
            margin-top: 14px;
            margin-bottom: 8px;
        }

        .item-table { width: 100%; margin-bottom: 2px; }
        .item-title { font-weight: bold; font-size: 10pt; color: #0f172a; }
        .item-date { text-align: right; font-size: 9pt; color: #64748b; white-space: nowrap; }
        .item-sub { font-weight: 600; font-size: 9pt; color: #059669; margin-bottom: 3px; }
        .item-desc { font-size: 9pt; color: #475569; margin-bottom: 10px; text-align: justify; }

        .skill-list { font-size: 9pt; color: #334155; }
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
        $organizations = $userData['organizations'] ?? [];
    @endphp

    <!-- HEADER -->
    <div class="header">
        <div class="name">{{ $cv['name'] ?? 'LIAM CONNOR' }}</div>
        <div class="job-title">{{ $cv['job_title'] ?? 'Computer Science Graduate | Aspiring Cloud Engineer' }}</div>
        <div class="contact">
            {{ $cv['email'] ?? 'liam.connor@graduate.edu' }}
            @if(!empty($cv['phone'])) <span>•</span> {{ $cv['phone'] }} @endif
            @if(!empty($cv['address'] ?? $cv['location'])) <span>•</span> {{ $cv['address'] ?? $cv['location'] }} @endif
            @if(!empty($cv['linkedin'])) <span>•</span> {{ $cv['linkedin'] }} @endif
            @if(!empty($cv['website'])) <span>•</span> {{ $cv['website'] }} @endif
        </div>
    </div>

    <!-- CAREER OBJECTIVE -->
    @if(!empty($cv['profile']))
        <div class="sec-title">Career Objective</div>
        <div class="item-desc">
            {{ $cv['profile'] }}
        </div>
    @endif

    <!-- EDUCATION (UPFRONT FOR GRADUATES) -->
    @if(!empty($educations))
        <div class="sec-title">Education</div>
        @foreach($educations as $edu)
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $edu['institution'] ?? '' }}</td>
                    <td class="item-date">{{ $edu['start_year'] ?? '' }} – {{ $edu['end_year'] ?? '' }}</td>
                </tr>
            </table>
            <div class="item-sub">{{ $edu['degree'] ?? '' }}{{ !empty($edu['major']) ? ' in ' . $edu['major'] : '' }}</div>
            @if(!empty($edu['description']))
                <div class="item-desc">{{ $edu['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- INTERNSHIPS & WORK EXPERIENCE -->
    @if(!empty($experiences))
        <div class="sec-title">Internships & Professional Experience</div>
        @foreach($experiences as $exp)
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $exp['position'] ?? '' }}</td>
                    <td class="item-date">
                        {{ $exp['start_year'] ?? '' }} – {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                    </td>
                </tr>
            </table>
            <div class="item-sub">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ' | ' . $exp['location'] : '' }}</div>
            @if(!empty($exp['description']))
                <div class="item-desc">
                    {!! nl2br(e($exp['description'])) !!}
                </div>
            @endif
        @endforeach
    @endif

    <!-- CAPSTONE & ACADEMIC PROJECTS -->
    @if(!empty($projects))
        <div class="sec-title">Academic & Capstone Projects</div>
        @foreach($projects as $prj)
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $prj['name'] ?? '' }}</td>
                    <td class="item-date">{{ $prj['link'] ?? '' }}</td>
                </tr>
            </table>
            @if(!empty($prj['technologies']))
                <div class="item-sub">Technologies: {{ $prj['technologies'] }}</div>
            @endif
            @if(!empty($prj['description']))
                <div class="item-desc">{{ $prj['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- LEADERSHIP & CAMPUS ACTIVITIES -->
    @if(!empty($organizations))
        <div class="sec-title">Leadership & Campus Activities</div>
        @foreach($organizations as $org)
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $org['role'] ?? '' }} — {{ $org['organization_name'] ?? '' }}</td>
                    <td class="item-date">{{ $org['period'] ?? '' }}</td>
                </tr>
            </table>
            @if(!empty($org['description']))
                <div class="item-desc">{{ $org['description'] }}</div>
            @endif
        @endforeach
    @endif

    <!-- TECHNICAL SKILLS -->
    @if(!empty($skills))
        <div class="sec-title">Technical Skills</div>
        <div class="skill-list">
            @php
                $skillNames = array_map(function($s) { return is_array($s) ? ($s['name'] ?? '') : (string)$s; }, $skills);
            @endphp
            {{ implode(' • ', array_filter($skillNames)) }}
        </div>
    @endif

</body>
</html>
