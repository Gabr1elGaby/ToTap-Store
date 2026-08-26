<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "ATS Friendly Standar BUMN & Nasional" }}</title>
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
            color: #111827;
            font-size: 8.5pt;
            line-height: 1.4;
        }
        .ats-page {
            width: 100%;
            padding: 35px 45px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        @media print {
            .ats-page {
                padding: 0;
            }
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

    <div class="ats-page">
        <!-- HEADER -->
        <div class="header">
            <div class="name">{{ $getVal($data, "name") ?: "NAMA LENGKAP" }}</div>
            <div class="job-title">{{ $getVal($data, "job_title") ?: "POSISI / PEKERJAAN" }}</div>
            <div class="contact-line">
                @if(!empty($data->phone))<span>{{ $data->phone }}</span> •@endif
                @if(!empty($data->email))<span>{{ $data->email }}</span> •@endif
                @if($getVal($data, "address", "location") !== "")<span>{{ $getVal($data, "address", "location") }}</span> •@endif
                @if(!empty($data->linkedin))<span>{{ $data->linkedin }}</span>@endif
            </div>
        </div>

        <!-- RINGKASAN PROFIL -->
        @if($getVal($data, "profile", "summary") !== "")
        <div class="section-heading">Ringkasan Profil</div>
        <div class="item-desc" style="margin-bottom: 8pt;">{{ $getVal($data, "profile", "summary") }}</div>
        @endif

        <!-- PENGALAMAN KERJA -->
        @if(!empty($experiences) && count($experiences) > 0)
        <div class="section-heading">Pengalaman Kerja</div>
        @foreach($experiences as $exp)
            @php
                $comp = is_array($exp) ? ($exp["company"] ?? "") : ($exp->company ?? "");
                $pos  = is_array($exp) ? ($exp["position"] ?? "") : ($exp->position ?? "");
                $start= is_array($exp) ? ($exp["start_year"] ?? "") : ($exp->start_year ?? "");
                $end  = is_array($exp) ? ($exp["end_year"] ?? "") : ($exp->end_year ?? "");
                $isCur= is_array($exp) ? ($exp["is_current"] ?? false) : ($exp->is_current ?? false);
                $dateStr = $start ? ($start . " - " . ($isCur ? "Sekarang" : ($end ?: "Sekarang"))) : ($end ?: "");
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

        <!-- RIWAYAT PENDIDIKAN -->
        @if(!empty($educations) && count($educations) > 0)
        <div class="section-heading">Riwayat Pendidikan</div>
        @foreach($educations as $edu)
            @php
                $inst = is_array($edu) ? ($edu["institution"] ?? "") : ($edu->institution ?? "");
                $deg  = is_array($edu) ? ($edu["degree"] ?? "") : ($edu->degree ?? "");
                $maj  = is_array($edu) ? ($edu["major"] ?? ($edu["field"] ?? "")) : ($edu->major ?? ($edu["field"] ?? ""));
                $start= is_array($edu) ? ($edu["start_year"] ?? "") : ($edu->start_year ?? "");
                $end  = is_array($edu) ? ($edu["end_year"] ?? "") : ($edu->end_year ?? "");
                $dateStr = $start ? ($start . " - " . ($end ?: "Sekarang")) : ($end ?: "");
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

        <!-- PROYEK -->
        @if(!empty($projects) && count($projects) > 0)
        <div class="section-heading">Proyek & Portofolio</div>
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
                @if(!empty($pSub))<div class="item-subtitle">{{ $pSub }}</div>@endif
                @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- MAGANG -->
        @if(!empty($internships) && count($internships) > 0)
        <div class="section-heading">Pengalaman Magang</div>
        @foreach($internships as $intern)
            @php
                $iComp = is_array($intern) ? ($intern["company"] ?? "") : ($intern->company ?? "");
                $iPos  = is_array($intern) ? ($intern["position"] ?? "") : ($intern->position ?? "");
                $iPer  = is_array($intern) ? ($intern["period"] ?? "") : ($intern->period ?? "");
                $iDesc = is_array($intern) ? ($intern["description"] ?? "") : ($intern->description ?? "");
            @endphp
            <div class="item-block">
                <table class="item-header-table">
                    <tr>
                        <td class="item-title">{{ $iPos }} — {{ $iComp }}</td>
                        <td class="item-date">{{ $iPer }}</td>
                    </tr>
                </table>
                @if(!empty($iDesc))<div class="item-desc">{!! nl2br(e($iDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- ORGANISASI -->
        @if(!empty($organizations) && count($organizations) > 0)
        <div class="section-heading">Pengalaman Organisasi</div>
        @foreach($organizations as $org)
            @php
                $oName = is_array($org) ? ($org["organization_name"] ?? "") : ($org->organization_name ?? "");
                $oRole = is_array($org) ? ($org["role"] ?? "") : ($org->role ?? "");
                $oPer  = is_array($org) ? ($org["period"] ?? "") : ($org->period ?? "");
                $oDesc = is_array($org) ? ($org["description"] ?? "") : ($org->description ?? "");
            @endphp
            <div class="item-block">
                <table class="item-header-table">
                    <tr>
                        <td class="item-title">{{ $oRole }} — {{ $oName }}</td>
                        <td class="item-date">{{ $oPer }}</td>
                    </tr>
                </table>
                @if(!empty($oDesc))<div class="item-desc">{!! nl2br(e($oDesc)) !!}</div>@endif
            </div>
        @endforeach
        @endif

        <!-- KEAHLIAN -->
        @if(!empty($skills) && count($skills) > 0)
        <div class="section-heading">Keahlian & Kompetensi</div>
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

        <!-- SERTIFIKASI -->
        @if(!empty($certificates) && count($certificates) > 0)
        <div class="section-heading">Sertifikasi & Pelatihan</div>
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