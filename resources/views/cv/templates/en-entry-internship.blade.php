<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "International Graduate & Internship Resume" }}</title>
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
            color: #1e293b;
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
            text-align: center;
            border-bottom: 2pt solid #059669;
            padding-bottom: 8pt;
            margin-bottom: 10pt;
        }
        .name {
            font-size: 16pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #065f46;
            margin-bottom: 3pt;
        }
        .job-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #059669;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 4pt;
        }
        .contact {
            font-size: 8pt;
            color: #64748b;
        }
        .contact span {
            margin: 0 4pt;
        }
        .sec-title {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #065f46;
            border-bottom: 1.5pt solid #059669;
            padding-bottom: 2pt;
            margin-top: 10pt;
            margin-bottom: 6pt;
        }
        .item-block {
            margin-bottom: 6pt;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .item-title {
            font-weight: bold;
            font-size: 8.5pt;
            color: #0f172a;
        }
        .item-date {
            text-align: right;
            font-size: 8pt;
            color: #64748b;
            white-space: nowrap;
        }
        .item-sub {
            font-weight: 600;
            font-size: 8pt;
            color: #059669;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8pt;
            color: #475569;
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
        <div class="header">
            <div class="name">{{ $getVal($data, "name") ?: "GRADUATE APPLICANT" }}</div>
            <div class="job-title">{{ $getVal($data, "job_title") ?: "GRADUATE ENGINEER & INTERN" }}</div>
            <div class="contact">
                @if(!empty($data->phone))<span>{{ $data->phone }}</span> •@endif
                @if(!empty($data->email))<span>{{ $data->email }}</span> •@endif
                @if($getVal($data, "address", "location") !== "")<span>{{ $getVal($data, "address", "location") }}</span> •@endif
                @if(!empty($data->linkedin))<span>{{ $data->linkedin }}</span>@endif
            </div>
        </div>

        <!-- CAREER OBJECTIVE -->
        @if($getVal($data, "profile", "summary") !== "")
        <div class="sec-title">Career Objective</div>
        <div class="item-desc" style="margin-bottom: 8pt;">{{ $getVal($data, "profile", "summary") }}</div>
        @endif

        <!-- EDUCATION (UPFRONT FOR GRADUATES) -->
        @if(!empty($educations) && count($educations) > 0)
        <div class="sec-title">Education & Academic Background</div>
        @foreach($educations as $edu)
            @php
                $inst = is_array($edu) ? ($edu["institution"] ?? "") : ($edu->institution ?? "");
                $deg  = is_array($edu) ? ($edu["degree"] ?? "") : ($edu->degree ?? "");
                $maj  = is_array($edu) ? ($edu["major"] ?? ($edu["field"] ?? "")) : ($edu->major ?? ($edu["field"] ?? ""));
                $start= is_array($edu) ? ($edu["start_year"] ?? "") : ($edu->start_year ?? "");
                $end  = is_array($edu) ? ($edu["end_year"] ?? "") : ($edu->end_year ?? "");
                $dateStr = $start ? ($start . " - " . ($end ?: "Present")) : ($end ?: "");
                $sub  = trim($deg . ($deg && $maj ? " in " : "") . $maj);
                $desc = is_array($edu) ? ($edu["description"] ?? "") : ($edu->description ?? "");
            @endphp
            <div class="item-block">
                <table class="item-table">
                    <tr>
                        <td class="item-title">{{ $inst }}</td>
                        <td class="item-date">{{ $dateStr }}</td>
                    </tr>
                </table>
                @if(!empty($sub))<div class="item-sub">{{ $sub }}</div>@endif
                @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- INTERNSHIPS & WORK EXPERIENCE -->
        @if(!empty($experiences) && count($experiences) > 0)
        <div class="sec-title">Internships & Professional Experience</div>
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
                <table class="item-table">
                    <tr>
                        <td class="item-title">{{ $pos }}</td>
                        <td class="item-date">{{ $dateStr }}</td>
                    </tr>
                </table>
                <div class="item-sub">{{ $comp }}</div>
                @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- ACADEMIC & CAPSTONE PROJECTS -->
        @if(!empty($projects) && count($projects) > 0)
        <div class="sec-title">Academic & Capstone Projects</div>
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
                @if(!empty($pSub))<div class="item-sub">{{ $pSub }}</div>@endif
                @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- LEADERSHIP & CAMPUS ACTIVITIES -->
        @if(!empty($organizations) && count($organizations) > 0)
        <div class="sec-title">Leadership & Campus Activities</div>
        @foreach($organizations as $org)
            @php
                $oName = is_array($org) ? ($org["organization_name"] ?? "") : ($org->organization_name ?? "");
                $oRole = is_array($org) ? ($org["role"] ?? "") : ($org->role ?? "");
                $oPer  = is_array($org) ? ($org["period"] ?? "") : ($org->period ?? "");
                $oDesc = is_array($org) ? ($org["description"] ?? "") : ($org->description ?? "");
            @endphp
            <div class="item-block">
                <table class="item-table">
                    <tr>
                        <td class="item-title">{{ $oRole }} — {{ $oName }}</td>
                        <td class="item-date">{{ $oPer }}</td>
                    </tr>
                </table>
                @if(!empty($oDesc))<div class="item-desc">{!! nl2br(e($oDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- TECHNICAL SKILLS -->
        @if(!empty($skills) && count($skills) > 0)
        <div class="sec-title">Technical Skills</div>
        <div class="item-desc">
            @php
                $skillNames = [];
                foreach($skills as $s) {
                    $n = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                    $l = is_array($s) ? ($s["level"] ?? "") : ($s->level ?? "");
                    if (!empty($n)) $skillNames[] = $n . ($l ? " (" . $l . "%)" : "");
                }
            @endphp
            {{ implode(" • ", $skillNames) }}
        </div>
        @endif
    </div>
</body>
</html>