<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Global Executive & Leadership CV" }}</title>
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
            font-family: Georgia, "Times New Roman", serif;
            color: #1f2937;
            font-size: 8.5pt;
            line-height: 1.45;
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
            text-align: center;
            border-bottom: 2pt double #1e3a8a;
            padding-bottom: 10pt;
            margin-bottom: 12pt;
        }
        .name {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #1e3a8a;
            margin-bottom: 3pt;
        }
        .job-title {
            font-size: 9.5pt;
            font-family: Arial, "Helvetica Neue", sans-serif;
            font-weight: bold;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5pt;
        }
        .contact-info {
            font-size: 8pt;
            font-family: Arial, "Helvetica Neue", sans-serif;
            color: #4b5563;
        }
        .contact-info span {
            margin: 0 4pt;
        }
        .sec-title {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #1e3a8a;
            border-bottom: 1pt solid #1e3a8a;
            padding-bottom: 2pt;
            margin-top: 10pt;
            margin-bottom: 6pt;
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
            font-size: 8.5pt;
            font-weight: bold;
            color: #111827;
        }
        .item-date {
            text-align: right;
            font-size: 8pt;
            font-family: Arial, "Helvetica Neue", sans-serif;
            color: #6b7280;
            white-space: nowrap;
        }
        .item-comp {
            font-style: italic;
            font-size: 8pt;
            color: #4b5563;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8pt;
            color: #374151;
            text-align: justify;
            line-height: 1.35;
        }
        .skills-grid {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, "Helvetica Neue", sans-serif;
            font-size: 8pt;
        }
        .skills-grid td {
            padding: 2pt 4pt;
            width: 33.33%;
            vertical-align: top;
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
            <div class="name">{{ $getVal($data, "name") ?: "EXECUTIVE CANDIDATE" }}</div>
            <div class="job-title">{{ $getVal($data, "job_title") ?: "VICE PRESIDENT & GLOBAL DIRECTOR" }}</div>
            <div class="contact-info">
                @if(!empty($data->phone))<span>{{ $data->phone }}</span> •@endif
                @if(!empty($data->email))<span>{{ $data->email }}</span> •@endif
                @if($getVal($data, "address", "location") !== "")<span>{{ $getVal($data, "address", "location") }}</span> •@endif
                @if(!empty($data->linkedin))<span>{{ $data->linkedin }}</span>@endif
            </div>
        </div>

        <!-- EXECUTIVE PROFILE -->
        @if($getVal($data, "profile", "summary") !== "")
        <div class="sec-title">Executive Profile & Vision</div>
        <div class="item-desc" style="margin-bottom: 8pt;">{{ $getVal($data, "profile", "summary") }}</div>
        @endif

        <!-- EXECUTIVE LEADERSHIP EXPERIENCE -->
        @if(!empty($experiences) && count($experiences) > 0)
        <div class="sec-title">Executive Leadership & Experience</div>
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
            <div class="item-block">
                <table class="item-header-table">
                    <tr>
                        <td class="item-title">{{ $pos }}</td>
                        <td class="item-date">{{ $dateStr }}</td>
                    </tr>
                </table>
                <div class="item-comp">{{ $comp }}</div>
                @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- CORE COMPETENCIES -->
        @if(!empty($skills) && count($skills) > 0)
        <div class="sec-title">Core Competencies & Strategic Capabilities</div>
        <table class="skills-grid">
            @php
                $skillNames = [];
                foreach($skills as $s) {
                    $n = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                    if (!empty($n)) $skillNames[] = $n;
                }
                $chunks = array_chunk($skillNames, 3);
            @endphp
            @foreach($chunks as $chunk)
            <tr>
                @foreach($chunk as $sk)
                <td>• <strong>{{ $sk }}</strong></td>
                @endforeach
            </tr>
            @endforeach
        </table>
        @endif

        <!-- EDUCATION -->
        @if(!empty($educations) && count($educations) > 0)
        <div class="sec-title">Education & Credentials</div>
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
            <div class="item-block">
                <table class="item-header-table">
                    <tr>
                        <td class="item-title">{{ $inst }}</td>
                        <td class="item-date">{{ $dateStr }}</td>
                    </tr>
                </table>
                @if(!empty($sub))<div class="item-comp">{{ $sub }}</div>@endif
                @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- KEY PROJECTS -->
        @if(!empty($projects) && count($projects) > 0)
        <div class="sec-title">Strategic Projects & Initiatives</div>
        @foreach($projects as $prj)
            @php
                $pName = is_array($prj) ? ($prj["name"] ?? "") : ($prj->name ?? "");
                $pRole = is_array($prj) ? ($prj["role"] ?? "") : ($prj->role ?? "");
                $pTech = is_array($prj) ? ($prj["technologies"] ?? "") : ($prj->technologies ?? "");
                $pLink = is_array($prj) ? ($prj["link"] ?? "") : ($prj->link ?? "");
                $pDesc = is_array($prj) ? ($prj["description"] ?? "") : ($prj->description ?? "");
                $pSub  = implode(" | ", array_filter([$pRole, $pTech, $pLink]));
            @endphp
            <div class="item-block">
                <div class="item-title">{{ $pName }}</div>
                @if(!empty($pSub))<div class="item-comp">{{ $pSub }}</div>@endif
                @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- CERTIFICATIONS -->
        @if(!empty($certificates) && count($certificates) > 0)
        <div class="sec-title">Executive Certifications & Advisory Roles</div>
        @foreach($certificates as $cert)
            @php
                $cName = is_array($cert) ? ($cert["name"] ?? "") : ($cert->name ?? "");
                $cIssuer = is_array($cert) ? ($cert["issuer"] ?? ($cert["publisher"] ?? "")) : ($cert->issuer ?? ($cert->publisher ?? ""));
                $cYear = is_array($cert) ? ($cert["year"] ?? "") : ($cert->year ?? "");
            @endphp
            <div class="item-block">
                <div class="item-title">{{ $cName }} @if($cIssuer)— {{ $cIssuer }}@endif @if($cYear)({{ $cYear }})@endif</div>
            </div>
        @endforeach
        @endif
    </div>
</body>
</html>