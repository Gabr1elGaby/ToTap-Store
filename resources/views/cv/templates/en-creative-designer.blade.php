<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Creative & UX Designer" }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            min-height: 100%;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #ffffff;
            color: #1e293b;
            font-size: 8pt;
            line-height: 1.35;
        }
        
        .cv-wrapper {
            position: relative;
            width: 100%;
            min-height: 100%;
            min-height: 1123px;
            background-color: #ffffff;
            box-sizing: border-box;
        }
        
        /* Full-Height Sidebar Strip for HTML preview */
        .sidebar-bg-strip {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            width: 32%;
            height: 100%;
            min-height: 100%;
            background-color: #18181b;
            z-index: 1;
        }
        
        /* Table Layout - LOCKED FIXED WIDTHS FOR PERFECT CENTERING */
        table.page1-table {
            position: relative;
            z-index: 2;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            min-height: 100%;
        }

        /* SIDEBAR (32%) */
        .sidebar-td {
            width: 32% !important;
            max-width: 32% !important;
            min-width: 32% !important;
            background-color: #18181b;
            color: #f4f4f5;
            padding: 22pt 14pt;
            vertical-align: top;
            box-sizing: border-box;
            text-align: left;
        }
        
        /* MAIN CONTENT (68%) */
        .content-td {
            width: 68% !important;
            max-width: 68% !important;
            min-width: 68% !important;
            background-color: #ffffff;
            padding: 22pt 20pt 20pt 18pt;
            vertical-align: top;
            box-sizing: border-box;
        }

        /* SIDEBAR ELEMENTS - PERFECTLY CENTERED AVATAR */
        .photo-container {
            text-align: center !important;
            margin-bottom: 12pt;
            width: 100%;
            display: block;
        }
        .photo {
            width: 72pt;
            height: 72pt;
            border-radius: 50%;
            object-fit: cover;
            border: 2.5pt solid #f43f5e;
            display: inline-block;
            margin: 0 auto;
        }
        .sidebar-heading {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #f43f5e;
            border-bottom: 1pt solid #27272a;
            padding-bottom: 2pt;
            margin-top: 9pt;
            margin-bottom: 5pt;
        }
        .contact-item {
            margin-bottom: 4.5pt;
            line-height: 1.25;
        }
        .contact-label {
            font-size: 6pt;
            text-transform: uppercase;
            color: #f43f5e;
            font-weight: bold;
            display: block;
        }
        .contact-val {
            font-size: 7.5pt;
            color: #ffffff;
            word-wrap: break-word;
        }
        
        /* SKILL PROGRESS BARS */
        .skill-container {
            margin-bottom: 6pt;
        }
        .skill-bar-item {
            margin-bottom: 4.5pt;
        }
        .skill-bar-header {
            font-size: 7pt;
            color: #f4f4f5;
            font-weight: 600;
            margin-bottom: 1.5pt;
            display: table;
            width: 100%;
        }
        .skill-name-cell {
            display: table-cell;
            text-align: left;
        }
        .skill-pct-cell {
            display: table-cell;
            text-align: right;
            color: #f43f5e;
            font-weight: bold;
            font-size: 6.5pt;
        }
        .skill-track {
            width: 100%;
            height: 3.5pt;
            background-color: rgba(255, 255, 255, 0.15);
            border-radius: 2pt;
            overflow: hidden;
        }
        .skill-fill {
            height: 3.5pt;
            background-color: #f43f5e;
            border-radius: 2pt;
        }
        .skill-bullet-item {
            font-size: 7pt;
            color: #f4f4f5;
            margin-bottom: 2.5pt;
            line-height: 1.2;
        }

        .cert-item {
            margin-bottom: 4.5pt;
            line-height: 1.2;
        }
        .cert-title {
            font-size: 7pt;
            font-weight: bold;
            color: #ffffff;
        }
        .cert-issuer {
            font-size: 6.5pt;
            color: #f4f4f5;
        }
        .cert-year {
            font-size: 6.5pt;
            color: #a1a1aa;
        }

        /* MAIN CONTENT ELEMENTS */
        .name {
            font-size: 15pt;
            font-weight: 800;
            color: #18181b;
            text-transform: uppercase;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 8pt;
            font-weight: bold;
            color: #e11d48;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 5pt;
        }
        .header-line {
            border: 0;
            border-top: 1.5pt solid #e11d48;
            margin: 0 0 7pt 0;
        }
        .summary {
            font-size: 7.5pt;
            color: #334155;
            text-align: justify;
            line-height: 1.35;
            margin-bottom: 8pt;
        }
        .right-heading {
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #18181b;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 2pt;
            margin-top: 8pt;
            margin-bottom: 5pt;
        }
        .item-block {
            margin-bottom: 6pt;
        }
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .item-title {
            font-size: 8pt;
            font-weight: bold;
            color: #18181b;
            text-align: left;
            vertical-align: top;
        }
        .item-date {
            font-size: 7pt;
            font-weight: bold;
            color: #e11d48;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 7.5pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 1.5pt;
        }
        .item-desc {
            font-size: 7pt;
            color: #334155;
            line-height: 1.3;
            text-align: justify;
        }
    </style>
</head>
<body>
    @php
        $getVal = function($obj, ...$keys) {
            foreach ($keys as $k) {
                if (is_object($obj) && isset($obj->$k) && !empty($obj->$k)) return $obj->$k;
                if (is_array($obj) && isset($obj[$k]) && !empty($obj[$k])) return $obj[$k];
            }
            return "";
        };

        $skills = (!empty($skills) && count($skills) > 0) ? $skills : ($data->skills ?? ($userData["skills"] ?? []));
        $educations = (!empty($educations) && count($educations) > 0) ? $educations : ($data->educations ?? ($userData["educations"] ?? []));
        $experiences = (!empty($experiences) && count($experiences) > 0) ? $experiences : ($data->experiences ?? ($userData["experiences"] ?? []));
        $projects = (!empty($projects) && count($projects) > 0) ? $projects : ($data->projects ?? ($userData["projects"] ?? []));
        $internships = (!empty($internships) && count($internships) > 0) ? $internships : ($data->internships ?? ($userData["internships"] ?? []));
        $organizations = (!empty($organizations) && count($organizations) > 0) ? $organizations : ($data->organizations ?? ($userData["organizations"] ?? []));
        $certificates = (!empty($certificates) && count($certificates) > 0) ? $certificates : ($data->certificates ?? ($userData["certificates"] ?? []));
        $tools = (!empty($tools) && count($tools) > 0) ? $tools : ($data->tools ?? ($userData["tools"] ?? []));
    @endphp

    <div class="cv-wrapper">
        <!-- Absolute Full-Height Sidebar Strip for HTML Live Preview -->
        <div class="sidebar-bg-strip"></div>

        <!-- 2-COLUMN CV TABLE WITH FIXED COLUMN WIDTHS -->
        <table class="page1-table" cellpadding="0" cellspacing="0">
            <colgroup>
                <col style="width: 32%;">
                <col style="width: 68%;">
            </colgroup>
            <tr>
                <!-- SIDEBAR -->
                <td class="sidebar-td">
                    @if(!empty($data->photo))
                    <div class="photo-container">
                        <img src="{{ $data->photo }}" class="photo" alt="Photo">
                    </div>
                    @endif

                    <div class="sidebar-heading" style="margin-top: 0;">Contact</div>
                    @if(!empty($data->phone))
                    <div class="contact-item">
                        <span class="contact-label">Phone / WhatsApp</span>
                        <span class="contact-val">{{ $data->phone }}</span>
                    </div>
                    @endif
                    @if(!empty($data->email))
                    <div class="contact-item">
                        <span class="contact-label">Email</span>
                        <span class="contact-val">{{ $data->email }}</span>
                    </div>
                    @endif
                    @if($getVal($data, "address", "location") !== "")
                    <div class="contact-item">
                        <span class="contact-label">Location</span>
                        <span class="contact-val">{{ $getVal($data, "address", "location") }}</span>
                    </div>
                    @endif
                    @if(!empty($data->linkedin))
                    <div class="contact-item">
                        <span class="contact-label">LinkedIn</span>
                        <span class="contact-val">{{ $data->linkedin }}</span>
                    </div>
                    @endif
                    @if(!empty($data->website))
                    <div class="contact-item">
                        <span class="contact-label">Portfolio / Website</span>
                        <span class="contact-val">{{ $data->website }}</span>
                    </div>
                    @endif

                    <!-- SKILLS WITH LEVEL PERCENTAGE BAR -->
                    @if(!empty($skills) && count($skills) > 0)
                    <div class="sidebar-heading">Core Skills</div>
                    <div class="skill-container">
                        @foreach($skills as $s)
                            @php
                                $sName = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                                $sLvl  = is_array($s) ? ($s["level"] ?? "") : ($s->level ?? "");
                            @endphp
                            @if(!empty($sName))
                                @if(!empty($sLvl) && is_numeric($sLvl) && $sLvl > 0)
                                <div class="skill-bar-item">
                                    <div class="skill-bar-header">
                                        <span class="skill-name-cell">{{ $sName }}</span>
                                        <span class="skill-pct-cell">{{ $sLvl }}%</span>
                                    </div>
                                    <div class="skill-track">
                                        <div class="skill-fill" style="width: {{ $sLvl }}%;"></div>
                                    </div>
                                </div>
                                @else
                                <div class="skill-bullet-item">• {{ $sName }}</div>
                                @endif
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <!-- TOOLS & TECHNOLOGIES -->
                    @if(!empty($tools) && count($tools) > 0)
                    <div class="sidebar-heading">Tools & Tech</div>
                    <div class="skill-container">
                        @foreach($tools as $tool)
                            @php
                                $tName = is_array($tool) ? ($tool["name"] ?? "") : ($tool->name ?? "");
                            @endphp
                            @if(!empty($tName))
                            <div class="skill-bullet-item">• {{ $tName }}</div>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    <!-- CERTIFICATIONS -->
                    @if(!empty($certificates) && count($certificates) > 0)
                    <div class="sidebar-heading">Certifications</div>
                    @foreach($certificates as $cert)
                        @php
                            $cName = is_array($cert) ? ($cert["name"] ?? "") : ($cert->name ?? "");
                            $cIssuer = is_array($cert) ? ($cert["issuer"] ?? ($cert["publisher"] ?? "")) : ($cert->issuer ?? ($cert->publisher ?? ""));
                            $cYear = is_array($cert) ? ($cert["year"] ?? "") : ($cert->year ?? "");
                        @endphp
                        @if(!empty($cName))
                        <div class="cert-item">
                            <div class="cert-title">{{ $cName }}</div>
                            @if(!empty($cIssuer))<div class="cert-issuer">{{ $cIssuer }}</div>@endif
                            @if(!empty($cYear))<div class="cert-year">{{ $cYear }}</div>@endif
                        </div>
                        @endif
                    @endforeach
                    @endif

                </td>

                <!-- MAIN CONTENT (68%) -->
                <td class="content-td">
                    
                    <!-- NAME & TITLE -->
                    <div class="name">{{ $getVal($data, "name") ?: "FULL NAME" }}</div>
                    <div class="job-title">{{ $getVal($data, "job_title") ?: "PROFESSIONAL TITLE" }}</div>
                    <hr class="header-line">

                    <!-- PROFESSIONAL SUMMARY -->
                    @if($getVal($data, "profile", "summary") !== "")
                    <div class="summary">
                        {{ $getVal($data, "profile", "summary") }}
                    </div>
                    @endif

                    <!-- WORK EXPERIENCE -->
                    @if(!empty($experiences) && count($experiences) > 0)
                    <div class="right-heading">Work Experience</div>
                    @foreach($experiences as $exp)
                        @php
                            $comp = is_array($exp) ? ($exp["company"] ?? "") : ($exp->company ?? "");
                            $pos  = is_array($exp) ? ($exp["position"] ?? "") : ($exp->position ?? "");
                            $start= is_array($exp) ? ($exp["start_year"] ?? "") : ($exp->start_year ?? "");
                            $end  = is_array($exp) ? ($exp["end_year"] ?? "") : ($exp->end_year ?? "");
                            $isCur= is_array($exp) ? ($exp["is_current"] ?? false) : ($exp->is_current ?? false);
                            $dateStr = $start ? ($start . " - " . ($isCur ? "Present" : ($end ?: "Present"))) : ($end ?: "");
                            $desc = is_array($exp) ? ($exp["description"] ?? "") : ($exp->description ?? "");
                        @endphp
                        @if(!empty($comp) || !empty($pos))
                        <div class="item-block">
                            <table class="item-header-table">
                                <tr>
                                    <td class="item-title">{{ $pos }}</td>
                                    <td class="item-date">{{ $dateStr }}</td>
                                </tr>
                            </table>
                            <div class="item-subtitle">{{ $comp }}</div>
                            @if(!empty($desc))
                            <div class="item-desc">{!! nl2br(e($desc)) !!}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    @endif

                    <!-- EDUCATION -->
                    @if(!empty($educations) && count($educations) > 0)
                    <div class="right-heading">Education</div>
                    @foreach($educations as $edu)
                        @php
                            $inst = is_array($edu) ? ($edu["institution"] ?? "") : ($edu->institution ?? "");
                            $deg  = is_array($edu) ? ($edu["degree"] ?? "") : ($edu->degree ?? "");
                            $maj  = is_array($edu) ? ($edu["major"] ?? ($edu["field"] ?? "")) : ($edu->major ?? ($edu["field"] ?? ""));
                            $start= is_array($edu) ? ($edu["start_year"] ?? "") : ($edu->start_year ?? "");
                            $end  = is_array($edu) ? ($edu["end_year"] ?? "") : ($edu->end_year ?? "");
                            $dateStr = $start ? ($start . " - " . ($end ?: "Present")) : ($end ?: "");
                            $sub  = trim($deg . ($deg && $maj ? " - " : "") . $maj);
                            $desc = is_array($edu) ? ($edu["description"] ?? "") : ($edu->description ?? "");
                        @endphp
                        @if(!empty($inst))
                        <div class="item-block">
                            <table class="item-header-table">
                                <tr>
                                    <td class="item-title">{{ $inst }}</td>
                                    <td class="item-date">{{ $dateStr }}</td>
                                </tr>
                            </table>
                            @if(!empty($sub))<div class="item-subtitle">{{ $sub }}</div>@endif
                            @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
                        </div>
                        @endif
                    @endforeach
                    @endif

                    <!-- KEY PROJECTS -->
                    @if(!empty($projects) && count($projects) > 0)
                    <div class="right-heading">Key Projects</div>
                    @foreach($projects as $proj)
                        @php
                            $pName = is_array($proj) ? ($proj["name"] ?? "") : ($proj->name ?? "");
                            $pTech = is_array($proj) ? ($proj["technologies"] ?? "") : ($proj->technologies ?? "");
                            $pLink = is_array($proj) ? ($proj["link"] ?? "") : ($proj->link ?? "");
                            $pDesc = is_array($proj) ? ($proj["description"] ?? "") : ($proj->description ?? "");
                        @endphp
                        @if(!empty($pName))
                        <div class="item-block">
                            <div class="item-title">{{ $pName }} @if($pLink)<span style="font-weight: normal; font-size: 6.5pt; color: #e11d48;">({{ $pLink }})</span>@endif</div>
                            @if(!empty($pTech))<div class="item-subtitle" style="font-size: 7pt;">{{ $pTech }}</div>@endif
                            @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
                        </div>
                        @endif
                    @endforeach
                    @endif

                    <!-- INTERNSHIPS -->
                    @if(!empty($internships) && count($internships) > 0)
                    <div class="right-heading">Internship Experience</div>
                    @foreach($internships as $intern)
                        @php
                            $comp = is_array($intern) ? ($intern["company"] ?? "") : ($intern->company ?? "");
                            $pos  = is_array($intern) ? ($intern["position"] ?? "") : ($intern->position ?? "");
                            $start= is_array($intern) ? ($intern["start_year"] ?? "") : ($intern->start_year ?? "");
                            $end  = is_array($intern) ? ($intern["end_year"] ?? ($intern["period"] ?? "")) : ($intern->end_year ?? ($intern->period ?? ""));
                            $dateStr = $start ? ($start . " - " . ($end ?: "Present")) : ($end ?: "");
                            $desc = is_array($intern) ? ($intern["description"] ?? "") : ($intern->description ?? "");
                        @endphp
                        @if(!empty($comp) || !empty($pos))
                        <div class="item-block">
                            <table class="item-header-table">
                                <tr>
                                    <td class="item-title">{{ $pos }}</td>
                                    <td class="item-date">{{ $dateStr }}</td>
                                </tr>
                            </table>
                            <div class="item-subtitle">{{ $comp }}</div>
                            @if(!empty($desc))
                            <div class="item-desc">{!! nl2br(e($desc)) !!}</div>
                            @endif
                        </div>
                        @endif
                    @endforeach
                    @endif

                </td>
            </tr>
        </table>
    </div>
</body>
</html>