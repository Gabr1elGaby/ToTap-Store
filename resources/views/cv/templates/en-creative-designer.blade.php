<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>International Creative & UX/UI Designer Resume</title>
    <style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #ffffff; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            padding: 35px 40px;
            font-size: 9.5pt;
            color: #27272a;
            line-height: 1.5;
            background-color: #fff;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }

        .name {
            font-size: 26pt;
            font-weight: 900;
            letter-spacing: -0.5px;
            color: #18181b;
            margin: 0 0 2px 0;
        }
        .job-title {
            font-size: 12pt;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 8px;
        }
        .contact-line {
            font-size: 8.5pt;
            color: #71717a;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f4f4f5;
        }
        .contact-line span { margin-right: 12px; }

        .main-table { width: 100%; }
        .col-left { width: 63%; padding-right: 25px; vertical-align: top; }
        .col-right { width: 37%; padding-left: 20px; vertical-align: top; border-left: 1.5px solid #f4f4f5; }

        .sec-title {
            font-size: 10.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #18181b;
            margin-bottom: 10px;
            margin-top: 14px;
            display: flex;
            align-items: center;
        }
        .sec-title:first-child { margin-top: 0; }

        .exp-item { margin-bottom: 14px; }
        .exp-pos { font-weight: 800; font-size: 10pt; color: #18181b; }
        .exp-comp { font-weight: 700; font-size: 9pt; color: #8b5cf6; }
        .exp-date { font-size: 8.5pt; color: #a1a1aa; text-align: right; }
        .exp-desc { font-size: 9pt; color: #52525b; margin-top: 3px; text-align: justify; }

        .prj-box {
            background-color: #faf5ff;
            border-left: 3px solid #8b5cf6;
            padding: 8px 12px;
            border-radius: 4px;
            margin-bottom: 10px;
        }
        .prj-name { font-weight: 800; font-size: 9.5pt; color: #581c87; }
        .prj-sub { font-size: 8pt; color: #7e22ce; font-weight: 600; margin-bottom: 2px; }
        .prj-desc { font-size: 8.5pt; color: #6b21a8; }

        .skill-pill {
            display: inline-block;
            background-color: #f4f4f5;
            color: #27272a;
            font-size: 8pt;
            font-weight: 600;
            padding: 4px 9px;
            border-radius: 6px;
            margin: 2px 2px 4px 0;
        }
        .side-edu { margin-bottom: 10px; }
        .side-edu-inst { font-weight: 800; font-size: 9pt; color: #18181b; }
        .side-edu-deg { font-size: 8.5pt; color: #8b5cf6; font-weight: 600; }
        .side-edu-date { font-size: 8pt; color: #a1a1aa; }
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
    <div class="name">{{ $cv['name'] ?? 'SARAH JENKINS' }}</div>
    <div class="job-title">{{ $cv['job_title'] ?? 'Senior Product & UX/UI Designer' }}</div>
    <div class="contact-line">
        <span>✉ {{ $cv['email'] ?? 'sarah.jenkins@portfolio.design' }}</span>
        @if(!empty($cv['phone'])) <span>📱 {{ $cv['phone'] }}</span> @endif
        @if(!empty($cv['address'] ?? $cv['location'])) <span>📍 {{ $cv['address'] ?? $cv['location'] }}</span> @endif
        @if(!empty($cv['website'])) <span>🎨 {{ $cv['website'] }}</span> @endif
        @if(!empty($cv['linkedin'])) <span>🔗 {{ $cv['linkedin'] }}</span> @endif
    </div>

    <!-- 2 COLUMNS -->
    <table class="main-table">
        <tr>
            <!-- LEFT MAIN: Experience & Projects -->
            <td class="col-left">
                
                @if(!empty($cv['profile']))
                    <div class="sec-title">About Me</div>
                    <div style="font-size: 9pt; color: #52525b; margin-bottom: 14px; text-align: justify;">
                        {{ $cv['profile'] }}
                    </div>
                @endif

                @if(!empty($experiences))
                    <div class="sec-title">Professional Experience</div>
                    @foreach($experiences as $exp)
                        <div class="exp-item">
                            <table style="width: 100%;">
                                <tr>
                                    <td class="exp-pos">{{ $exp['position'] ?? '' }}</td>
                                    <td class="exp-date">
                                        {{ $exp['start_year'] ?? '' }} - {{ !empty($exp['is_current']) ? 'Present' : ($exp['end_year'] ?? '') }}
                                    </td>
                                </tr>
                            </table>
                            <div class="exp-comp">{{ $exp['company'] ?? '' }}{{ !empty($exp['location']) ? ' | ' . $exp['location'] : '' }}</div>
                            @if(!empty($exp['description']))
                                <div class="exp-desc">
                                    {!! nl2br(e($exp['description'])) !!}
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                @if(!empty($projects))
                    <div class="sec-title">Featured Design & UX Case Studies</div>
                    @foreach($projects as $prj)
                        <div class="prj-box">
                            <div class="prj-name">{{ $prj['name'] ?? '' }}</div>
                            @if(!empty($prj['technologies']))
                                <div class="prj-sub">{{ $prj['technologies'] }}</div>
                            @endif
                            @if(!empty($prj['description']))
                                <div class="prj-desc">{{ $prj['description'] }}</div>
                            @endif
                        </div>
                    @endforeach
                @endif

            </td>

            <!-- RIGHT SIDEBAR: Skills, Education, Certs -->
            <td class="col-right">
                
                <!-- DESIGN TOOLS & SKILLS -->
                @if(!empty($skills))
                    <div class="sec-title">Skills & Expertise</div>
                    <div style="margin-bottom: 16px;">
                        @foreach($skills as $skill)
                            @php
                                $sName = is_array($skill) ? ($skill['name'] ?? '') : (string)$skill;
                            @endphp
                            @if(!empty($sName))
                                <span class="skill-pill">{{ $sName }}</span>
                            @endif
                        @endforeach
                    </div>
                @endif

                <!-- EDUCATION -->
                @if(!empty($educations))
                    <div class="sec-title">Education</div>
                    @foreach($educations as $edu)
                        <div class="side-edu">
                            <div class="side-edu-inst">{{ $edu['institution'] ?? '' }}</div>
                            <div class="side-edu-deg">{{ $edu['degree'] ?? '' }}{{ !empty($edu['major'] ?? $edu['field'] ?? null) ? ' in ' . ($edu['major'] ?? $edu['field']) : '' }}</div>
                            <div class="side-edu-date">{{ $edu['start_year'] ?? '' }} - {{ $edu['end_year'] ?? '' }}</div>
                        </div>
                    @endforeach
                @endif

                <!-- CERTIFICATIONS & HONORS -->
                @if(!empty($certificates))
                    <div class="sec-title">Certifications</div>
                    @foreach($certificates as $cert)
                        <div class="side-edu">
                            <div class="side-edu-inst">{{ $cert['name'] ?? '' }}</div>
                            <div class="side-edu-date">{{ $cert['issuer'] ?? '' }} ({{ $cert['year'] ?? '' }})</div>
                        </div>
                    @endforeach
                @endif

            </td>
        </tr>
    </table>

</body>
</html>
