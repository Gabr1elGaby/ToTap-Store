<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Silicon Valley Modern Tech Resume</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #f8fafc; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 9.5pt;
            color: #1e293b;
            line-height: 1.45;
            background-color: #fff;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        
        .header-bar {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: #ffffff;
            padding: 30px 40px;
        }
        .name {
            font-size: 22pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #ffffff;
            margin-bottom: 4px;
        }
        .job-title {
            font-size: 11pt;
            font-weight: 700;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }
        .contact-grid {
            font-size: 8.5pt;
            color: #cbd5e1;
        }
        .contact-grid span {
            margin-right: 15px;
        }

        .content-table {
            width: 100%;
        }
        .left-col {
            width: 34%;
            background-color: #f8fafc;
            padding: 24px 20px 24px 35px;
            vertical-align: top;
            border-right: 1.5px solid #e2e8f0;
        }
        .right-col {
            width: 66%;
            padding: 24px 35px 24px 25px;
            vertical-align: top;
        }

        .section-title {
            font-size: 10.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 3px;
            margin-bottom: 10px;
            margin-top: 6px;
        }
        .side-section-title {
            font-size: 9.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #0f172a;
            border-bottom: 1.5px solid #cbd5e1;
            padding-bottom: 3px;
            margin-bottom: 8px;
            margin-top: 14px;
        }
        .side-section-title:first-child {
            margin-top: 0;
        }

        .item-title {
            font-weight: bold;
            font-size: 10pt;
            color: #0f172a;
        }
        .item-date {
            text-align: right;
            font-size: 8.5pt;
            color: #64748b;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 9pt;
            color: #0284c7;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 9pt;
            color: #475569;
            margin-bottom: 12px;
            text-align: justify;
        }

        .skill-tag {
            display: inline-block;
            background-color: #e0f2fe;
            color: #0369a1;
            font-size: 8pt;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 5px;
            margin-right: 3px;
        }
        .side-item {
            margin-bottom: 10px;
        }
        .side-bold {
            font-weight: bold;
            font-size: 9pt;
            color: #1e293b;
        }
        .side-sub {
            font-size: 8.5pt;
            color: #64748b;
        }
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

    <!-- TOP HEADER -->
    <div class="header-bar">
        <div class="name">{{ $cv['name'] ?? 'ALEXANDER WRIGHT' }}</div>
        @if(!empty($cv['job_title']))
            <div class="job-title">{{ $cv['job_title'] }}</div>
        @endif
        <div class="contact-grid">
            <span>✉ {{ $cv['email'] ?? 'alex.wright@example.com' }}</span>
            @if(!empty($cv['phone'])) <span>📱 {{ $cv['phone'] }}</span> @endif
            @if(!empty($cv['address'] ?? $cv['location'])) <span>📍 {{ $cv['address'] ?? $cv['location'] }}</span> @endif
            @if(!empty($cv['linkedin'])) <span>🔗 {{ $cv['linkedin'] }}</span> @endif
            @if(!empty($cv['website'])) <span>🌐 {{ $cv['website'] }}</span> @endif
        </div>
    </div>

    <!-- 2 COLUMN CONTENT -->
    <table class="content-table">
        <tr>
            <!-- LEFT SIDEBAR -->
            <td class="left-col">
                
                <!-- EDUCATION -->
                @if(!empty($educations))
                    <div class="side-section-title">Education</div>
                    @foreach($educations as $edu)
                        <div class="side-item">
                            <div class="side-bold">{{ $edu['institution'] ?? '' }}</div>
                            <div class="side-sub">{{ $edu['degree'] ?? '' }}{{ !empty($edu['major']) ? ' - ' . $edu['major'] : '' }}</div>
                            <div class="side-sub" style="font-size: 8pt; color: #94a3b8;">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? '' }}</div>
                            @if(!empty($edu['description']))
                                <div style="font-size: 8pt; color: #64748b; margin-top: 2px;">{{ $edu['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <!-- TECHNICAL SKILLS -->
                @if(!empty($skills))
                    <div class="side-section-title">Tech Stack & Skills</div>
                    <div style="margin-top: 6px;">
                        @foreach($skills as $skill)
                            @php
                                $sName = is_array($skill) ? ($skill['name'] ?? '') : (string)$skill;
                            @endphp
                            @if(!empty($sName))
                                <span class="skill-tag">{{ $sName }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- CERTIFICATIONS -->
                @if(!empty($certificates))
                    <div class="side-section-title">Certifications</div>
                    @foreach($certificates as $cert)
                        <div class="side-item">
                            <div class="side-bold">{{ $cert['name'] ?? '' }}</div>
                            <div class="side-sub">{{ $cert['issuer'] ?? '' }} ({{ $cert['year'] ?? '' }})</div>
                        </div>
                    @endforeach
                @endif

            </td>

            <!-- RIGHT MAIN COLUMN -->
            <td class="right-col">
                
                <!-- SUMMARY -->
                @if(!empty($cv['profile']))
                    <div class="section-title">Executive Summary</div>
                    <div class="item-desc">
                        {{ $cv['profile'] }}
                    </div>
                @endif

                <!-- WORK EXPERIENCE -->
                @if(!empty($experiences))
                    <div class="section-title">Professional Experience</div>
                    @foreach($experiences as $exp)
                        <table style="width: 100%; margin-bottom: 2px;">
                            <tr>
                                <td class="item-title">{{ $exp['position'] ?? '' }}</td>
                                <td class="item-date">
                                    {{ $exp['start_year'] ?? '' }} - {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                                </td>
                            </tr>
                        </table>
                        <div class="item-subtitle">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ' • ' . $exp['location'] : '' }}</div>
                        @if(!empty($exp['description']))
                            <div class="item-desc">
                                {!! nl2br(e($exp['description'])) !!}
                            </div>
                        @endif
                    @endforeach
                @endif

                <!-- KEY PROJECTS -->
                @if(!empty($projects))
                    <div class="section-title">Featured Projects</div>
                    @foreach($projects as $prj)
                        <table style="width: 100%; margin-bottom: 2px;">
                            <tr>
                                <td class="item-title">{{ $prj['name'] ?? '' }}</td>
                                <td class="item-date">{{ $prj['link'] ?? '' }}</td>
                            </tr>
                        </table>
                        @if(!empty($prj['technologies']))
                            <div class="item-subtitle" style="color: #64748b; font-size: 8.5pt;">Stack: {{ $prj['technologies'] }}</div>
                        @endif
                        @if(!empty($prj['description']))
                            <div class="item-desc">
                                {{ $prj['description'] }}
                            </div>
                        @endif
                    @endforeach
                @endif

            </td>
        </tr>
    </table>

</body>
</html>
