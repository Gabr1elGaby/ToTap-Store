<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Academic, Research & Fellowship CV" }}</title>
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
            font-family: "Times New Roman", Times, serif;
            color: #000000;
            font-size: 9pt;
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
        .name {
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 3pt;
        }
        .contact {
            text-align: center;
            font-size: 8pt;
            margin-bottom: 12pt;
            border-bottom: 1pt solid #000;
            padding-bottom: 6pt;
        }
        .contact span {
            margin: 0 4pt;
        }
        .sec-head {
            font-size: 9.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            border-bottom: 1pt solid #000;
            margin-top: 10pt;
            margin-bottom: 6pt;
            padding-bottom: 2pt;
        }
        .row-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2pt;
        }
        .item-main {
            font-weight: bold;
            font-size: 9pt;
        }
        .item-year {
            text-align: right;
            white-space: nowrap;
            font-size: 8.5pt;
        }
        .item-sub {
            font-style: italic;
            margin-bottom: 2pt;
            font-size: 8.5pt;
        }
        .item-body {
            font-size: 8.5pt;
            margin-bottom: 6pt;
            text-align: justify;
            line-height: 1.35;
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
        <div class="name">{{ $getVal($data, "name") ?: "SCHOLAR & RESEARCH CANDIDATE" }}</div>
        <div class="contact">
            @if(!empty($data->phone))<span>{{ $data->phone }}</span> •@endif
            @if(!empty($data->email))<span>{{ $data->email }}</span> •@endif
            @if($getVal($data, "address", "location") !== "")<span>{{ $getVal($data, "address", "location") }}</span> •@endif
            @if(!empty($data->linkedin))<span>{{ $data->linkedin }}</span>@endif
        </div>

        <!-- RESEARCH INTERESTS -->
        @if($getVal($data, "profile", "summary") !== "")
        <div class="sec-head">Research Interests & Scholarly Profile</div>
        <div class="item-body">{{ $getVal($data, "profile", "summary") }}</div>
        @endif

        <!-- EDUCATION (ACADEMIC APPOINTMENTS) -->
        @if(!empty($educations) && count($educations) > 0)
        <div class="sec-head">Academic Appointments & Education</div>
        @foreach($educations as $edu)
            @php
                $inst = is_array($edu) ? ($edu["institution"] ?? "") : ($edu->institution ?? "");
                $deg  = is_array($edu) ? ($edu["degree"] ?? "") : ($edu->degree ?? "");
                $maj  = is_array($edu) ? ($edu["major"] ?? ($edu["field"] ?? "")) : ($edu->major ?? ($edu["field"] ?? ""));
                $start= is_array($edu) ? ($edu["start_year"] ?? "") : ($edu->start_year ?? "");
                $end  = is_array($edu) ? ($edu["end_year"] ?? "") : ($edu->end_year ?? "");
                $dateStr = $start ? ($start . " - " . ($end ?: "Present")) : ($end ?: "");
                $sub  = trim($deg . ($deg && $maj ? ", " : "") . $maj);
                $desc = is_array($edu) ? ($edu["description"] ?? "") : ($edu->description ?? "");
            @endphp
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $inst }}</td>
                    <td class="item-year">{{ $dateStr }}</td>
                </tr>
            </table>
            @if(!empty($sub))<div class="item-sub">{{ $sub }}</div>@endif
            @if(!empty($desc))<div class="item-body">{!! nl2br(e($desc)) !!}</div>@endif
        @endforeach
        @endif

        <!-- TEACHING & RESEARCH EXPERIENCE -->
        @if(!empty($experiences) && count($experiences) > 0)
        <div class="sec-head">Research & Teaching Experience</div>
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
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $pos }}</td>
                    <td class="item-year">{{ $dateStr }}</td>
                </tr>
            </table>
            <div class="item-sub">{{ $comp }}</div>
            @if(!empty($desc))<div class="item-body">{!! nl2br(e($desc)) !!}</div>@endif
        @endforeach
        @endif

        <!-- PUBLICATIONS & RESEARCH PROJECTS -->
        @if(!empty($projects) && count($projects) > 0)
        <div class="sec-head">Selected Publications & Research Grants</div>
        @foreach($projects as $prj)
            @php
                $pName = is_array($prj) ? ($prj["name"] ?? "") : ($prj->name ?? "");
                $pRole = is_array($prj) ? ($prj["role"] ?? "") : ($prj->role ?? "");
                $pTech = is_array($prj) ? ($prj["technologies"] ?? "") : ($prj->technologies ?? "");
                $pLink = is_array($prj) ? ($prj["link"] ?? "") : ($prj->link ?? "");
                $pDesc = is_array($prj) ? ($prj["description"] ?? "") : ($prj->description ?? "");
                $pSub  = implode(" | ", array_filter([$pRole, $pTech, $pLink]));
            @endphp
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $pName }}</td>
                    <td class="item-year">{{ $pLink }}</td>
                </tr>
            </table>
            @if(!empty($pSub))<div class="item-sub">{{ $pSub }}</div>@endif
            @if(!empty($pDesc))<div class="item-body">{!! nl2br(e($pDesc)) !!}</div>@endif
        @endforeach
        @endif

        <!-- HONORS & FELLOWSHIPS -->
        @if(!empty($certificates) && count($certificates) > 0)
        <div class="sec-head">Fellowships, Honors & Grants</div>
        @foreach($certificates as $cert)
            @php
                $cName = is_array($cert) ? ($cert["name"] ?? "") : ($cert->name ?? "");
                $cIssuer = is_array($cert) ? ($cert["issuer"] ?? ($cert["publisher"] ?? "")) : ($cert->issuer ?? ($cert->publisher ?? ""));
                $cYear = is_array($cert) ? ($cert["year"] ?? "") : ($cert->year ?? "");
            @endphp
            <table class="row-table">
                <tr>
                    <td class="item-main">{{ $cName }}</td>
                    <td class="item-year">{{ $cYear }}</td>
                </tr>
            </table>
            @if(!empty($cIssuer))<div class="item-sub">{{ $cIssuer }}</div>@endif
        @endforeach
        @endif

        <!-- METHODOLOGIES & SKILLS -->
        @if(!empty($skills) && count($skills) > 0)
        <div class="sec-head">Methodologies, Tools & Languages</div>
        <div class="item-body" style="margin-top: 4pt;">
            @php
                $skillNames = [];
                foreach($skills as $s) {
                    $n = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                    if (!empty($n)) $skillNames[] = $n;
                }
            @endphp
            {{ implode(" • ", $skillNames) }}
        </div>
        @endif
    </div>
</body>
</html>