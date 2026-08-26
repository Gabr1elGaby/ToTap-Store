<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "International Creative & UX/UI" }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 15mm 18mm 15mm 18mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        html, body {
            margin: 0;
            padding: 0;
            background-color: #ffffff;
            font-family: Arial, "Helvetica Neue", sans-serif;
            color: #18181b;
            font-size: 8.5pt;
            line-height: 1.4;
        }
        .page-container {
            width: 100%;
            padding: 35px 45px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        @media print {
            .page-container {
                padding: 0;
            }
        }
        .header {
            margin-bottom: 12pt;
            padding-bottom: 8pt;
            border-bottom: 2pt solid #8b5cf6;
        }
        .name {
            font-size: 18pt;
            font-weight: 900;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: #18181b;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #8b5cf6;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4pt;
        }
        .contact-line {
            font-size: 8pt;
            color: #71717a;
        }
        .contact-line span {
            margin-right: 8pt;
        }
        .main-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .col-left {
            width: 63%;
            padding-right: 14pt;
            vertical-align: top;
        }
        .col-right {
            width: 37%;
            padding-left: 14pt;
            vertical-align: top;
            border-left: 1.5pt solid #f4f4f5;
        }
        .sec-title {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #18181b;
            margin-bottom: 6pt;
            margin-top: 10pt;
        }
        .sec-title:first-child {
            margin-top: 0;
        }
        .exp-item {
            margin-bottom: 8pt;
        }
        .exp-pos {
            font-weight: 800;
            font-size: 8.5pt;
            color: #18181b;
        }
        .exp-comp {
            font-weight: bold;
            font-size: 8pt;
            color: #8b5cf6;
            margin-bottom: 1pt;
        }
        .exp-date {
            font-size: 7.5pt;
            color: #a1a1aa;
            text-align: right;
            white-space: nowrap;
        }
        .exp-desc {
            font-size: 8pt;
            color: #52525b;
            text-align: justify;
            line-height: 1.35;
        }
        .prj-box {
            background-color: #faf5ff;
            border-left: 2.5pt solid #8b5cf6;
            padding: 5pt 8pt;
            border-radius: 4px;
            margin-bottom: 6pt;
        }
        .prj-name {
            font-weight: 800;
            font-size: 8.5pt;
            color: #581c87;
        }
        .prj-sub {
            font-size: 7.5pt;
            color: #7e22ce;
            font-weight: bold;
            margin-bottom: 2pt;
        }
        .prj-desc {
            font-size: 7.5pt;
            color: #6b21a8;
            line-height: 1.35;
        }
        .skill-pill {
            display: inline-block;
            background-color: #f4f4f5;
            color: #27272a;
            font-size: 7.5pt;
            font-weight: bold;
            padding: 2.5pt 6pt;
            border-radius: 4px;
            margin: 1.5pt 1.5pt 3pt 0;
        }
        .side-edu {
            margin-bottom: 6pt;
        }
        .side-edu-inst {
            font-weight: 800;
            font-size: 8pt;
            color: #18181b;
        }
        .side-edu-deg {
            font-size: 7.5pt;
            color: #8b5cf6;
            font-weight: bold;
        }
        .side-edu-date {
            font-size: 7.5pt;
            color: #a1a1aa;
        }
    </style>
</head>
<body>
    @php
        $getVal = function($obj, ...$keys) use ($userData) {
            if (isset($userData['cv'])) {
                foreach ($keys as $k) {
                    if (isset($userData['cv'][$k]) && !empty($userData['cv'][$k])) return $userData['cv'][$k];
                    if (isset($userData['cv']->$k) && !empty($userData['cv']->$k)) return $userData['cv']->$k;
                }
            }
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
    @endphp

    <div class="page-container">
        <!-- HEADER -->
        <div class="header">
            <div class="name">{{ $getVal($data, "name") ?: "SARAH JENKINS" }}</div>
            <div class="job-title">{{ $getVal($data, "job_title") ?: "PRODUCT & UX/UI DESIGNER" }}</div>
            <div class="contact-line">
                @if(!empty($data->phone))<span>📱 {{ $data->phone }}</span>@endif
                @if(!empty($data->email))<span>✉ {{ $data->email }}</span>@endif
                @if($getVal($data, "address", "location") !== "")<span>📍 {{ $getVal($data, "address", "location") }}</span>@endif
                @if(!empty($data->linkedin))<span>🔗 {{ $data->linkedin }}</span>@endif
            </div>
        </div>

        <!-- 2 COLUMNS -->
        <table class="main-table">
            <tr>
                <!-- LEFT COLUMN: Experience & Projects -->
                <td class="col-left">
                    @if($getVal($data, "profile", "summary") !== "")
                    <div class="sec-title">About Me</div>
                    <div class="exp-desc" style="margin-bottom: 8pt;">{{ $getVal($data, "profile", "summary") }}</div>
                    @endif

                    @if(!empty($experiences) && count($experiences) > 0)
                    <div class="sec-title">Professional Experience</div>
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
                        <div class="exp-item">
                            <table style="width: 100%;">
                                <tr>
                                    <td class="exp-pos">{{ $pos }}</td>
                                    <td class="exp-date">{{ $dateStr }}</td>
                                </tr>
                            </table>
                            <div class="exp-comp">{{ $comp }}</div>
                            @if(!empty($desc))<div class="exp-desc">{!! nl2br(e($desc)) !!}</div>@endif
                        </div>
                    @endforeach
                    @endif

                    @if(!empty($projects) && count($projects) > 0)
                    <div class="sec-title">Featured Design & UX Case Studies</div>
                    @foreach($projects as $prj)
                        @php
                            $pName = is_array($prj) ? ($prj["name"] ?? "") : ($prj->name ?? "");
                            $pRole = is_array($prj) ? ($prj["role"] ?? "") : ($prj->role ?? "");
                            $pTech = is_array($prj) ? ($prj["technologies"] ?? "") : ($prj->technologies ?? "");
                            $pLink = is_array($prj) ? ($prj["link"] ?? "") : ($prj->link ?? "");
                            $pDesc = is_array($prj) ? ($prj["description"] ?? "") : ($prj->description ?? "");
                            $pSub  = implode(" | ", array_filter([$pRole, $pTech, $pLink]));
                        @endphp
                        <div class="prj-box">
                            <div class="prj-name">{{ $pName }}</div>
                            @if(!empty($pSub))<div class="prj-sub">{{ $pSub }}</div>@endif
                            @if(!empty($pDesc))<div class="prj-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
                        </div>
                    @endforeach
                    @endif
                </td>

                <!-- RIGHT COLUMN: Skills, Education, Certs -->
                <td class="col-right">
                    @if(!empty($skills) && count($skills) > 0)
                    <div class="sec-title">Skills & Expertise</div>
                    <div style="margin-bottom: 8pt;">
                        @foreach($skills as $skill)
                            @php
                                $sName = is_array($skill) ? ($skill["name"] ?? "") : ($skill->name ?? "");
                            @endphp
                            @if(!empty($sName))
                                <span class="skill-pill">{{ $sName }}</span>
                            @endif
                        @endforeach
                    </div>
                    @endif

                    @if(!empty($educations) && count($educations) > 0)
                    <div class="sec-title">Education</div>
                    @foreach($educations as $edu)
                        @php
                            $inst = is_array($edu) ? ($edu["institution"] ?? "") : ($edu->institution ?? "");
                            $deg  = is_array($edu) ? ($edu["degree"] ?? "") : ($edu->degree ?? "");
                            $maj  = is_array($edu) ? ($edu["major"] ?? ($edu["field"] ?? "")) : ($edu->major ?? ($edu["field"] ?? ""));
                            $start= is_array($edu) ? ($edu["start_year"] ?? "") : ($edu->start_year ?? "");
                            $end  = is_array($edu) ? ($edu["end_year"] ?? "") : ($edu->end_year ?? "");
                            $dateStr = $start ? ($start . " - " . ($end ?: "Present")) : ($end ?: "");
                            $sub  = trim($deg . ($deg && $maj ? " in " : "") . $maj);
                        @endphp
                        <div class="side-edu">
                            <div class="side-edu-inst">{{ $inst }}</div>
                            @if(!empty($sub))<div class="side-edu-deg">{{ $sub }}</div>@endif
                            <div class="side-edu-date">{{ $dateStr }}</div>
                        </div>
                    @endforeach
                    @endif

                    @if(!empty($certificates) && count($certificates) > 0)
                    <div class="sec-title">Certifications</div>
                    @foreach($certificates as $cert)
                        @php
                            $cName = is_array($cert) ? ($cert["name"] ?? "") : ($cert->name ?? "");
                            $cIssuer = is_array($cert) ? ($cert["issuer"] ?? ($cert["publisher"] ?? "")) : ($cert->issuer ?? ($cert->publisher ?? ""));
                            $cYear = is_array($cert) ? ($cert["year"] ?? "") : ($cert->year ?? "");
                        @endphp
                        <div class="side-edu">
                            <div class="side-edu-inst">{{ $cName }}</div>
                            <div class="side-edu-date">{{ $cIssuer }} @if($cYear)({{ $cYear }})@endif</div>
                        </div>
                    @endforeach
                    @endif
                </td>
            </tr>
        </table>
    </div>
</body>
</html>