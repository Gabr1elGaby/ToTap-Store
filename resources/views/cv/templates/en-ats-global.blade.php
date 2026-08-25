<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Global ATS Standard Resume" }}</title>
    <style>
        @page {
            size: A4;
            margin: 20mm 18mm 20mm 18mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, "Helvetica Neue", sans-serif;
            background-color: #ffffff;
            color: #111827;
            font-size: 8.5pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2pt solid #111827;
            padding-bottom: 8pt;
            margin-bottom: 10pt;
        }
        .name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000000;
            margin-bottom: 3pt;
        }
        .job-title {
            font-size: 9.5pt;
            font-weight: bold;
            color: #374151;
            text-transform: uppercase;
            margin-bottom: 4pt;
        }
        .contact-line {
            font-size: 8pt;
            color: #4b5563;
        }
        .contact-line span {
            margin: 0 4pt;
        }
        .section-heading {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #000000;
            border-bottom: 1pt solid #9ca3af;
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
            color: #000000;
            text-align: left;
        }
        .item-date {
            font-size: 8pt;
            color: #4b5563;
            text-align: right;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 8pt;
            font-style: italic;
            color: #374151;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8pt;
            color: #1f2937;
            text-align: justify;
            line-height: 1.35;
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
    @endphp

    <div class="header">
        <div class="name">{{ $getVal($data, "name") ?: "FULL NAME" }}</div>
        <div class="job-title">{{ $getVal($data, "job_title") ?: "PROFESSIONAL TITLE" }}</div>
        <div class="contact-line">
            @if(!empty($data->phone))<span>{{ $data->phone }}</span> •@endif
            @if(!empty($data->email))<span>{{ $data->email }}</span> •@endif
            @if($getVal($data, "address", "location") !== "")<span>{{ $getVal($data, "address", "location") }}</span> •@endif
            @if(!empty($data->linkedin))<span>{{ $data->linkedin }}</span>@endif
        </div>
    </div>

    @if($getVal($data, "profile", "summary") !== "")
    <div class="section-heading">Professional Summary</div>
    <div class="item-desc" style="margin-bottom: 8pt;">{{ $getVal($data, "profile", "summary") }}</div>
    @endif

    @if(!empty($experiences) && count($experiences) > 0)
    <div class="section-heading">Professional Experience</div>
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
                    <td class="item-title">{{ $pos }} — {{ $comp }}</td>
                    <td class="item-date">{{ $dateStr }}</td>
                </tr>
            </table>
            @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
        </div>
    @endforeach
    @endif

    @if(!empty($educations) && count($educations) > 0)
    <div class="section-heading">Education</div>
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
            @if(!empty($sub))<div class="item-subtitle">{{ $sub }}</div>@endif
            @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
        </div>
    @endforeach
    @endif

    @if(!empty($skills) && count($skills) > 0)
    <div class="section-heading">Technical & Core Skills</div>
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

    @if(!empty($certificates) && count($certificates) > 0)
    <div class="section-heading">Certifications & Licenses</div>
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

</body>
</html>