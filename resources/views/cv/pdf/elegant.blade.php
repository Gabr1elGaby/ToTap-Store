<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Elegan Eksekutif" }}</title>
    <style>
        @page {
            size: a4 portrait;
            margin: 12mm 15mm 12mm 15mm;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: Georgia, 'Times New Roman', serif;
            color: #1e293b;
            font-size: 8pt;
            line-height: 1.35;
            background: #ffffff;
        }
        .header-box {
            border-bottom: 2pt solid #b45309;
            padding-bottom: 8pt;
            margin-bottom: 9pt;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-photo-td {
            width: 60pt;
            vertical-align: top;
            text-align: left;
            padding-right: 12pt;
        }
        .header-photo {
            width: 54pt;
            height: 54pt;
            border-radius: 50%;
            object-fit: cover;
            border: 2pt solid #b45309;
        }
        .name {
            font-size: 16pt;
            font-weight: 800;
            color: #1c1917;
            text-transform: uppercase;
            line-height: 1.1;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5pt;
        }
        .contact-list {
            font-size: 7.5pt;
            color: #475569;
            line-height: 1.35;
        }
        .contact-item {
            display: inline-block;
            margin-right: 12pt;
            margin-bottom: 2pt;
        }
        .section-heading {
            font-size: 8.5pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #1c1917;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 2pt;
            margin-top: 8pt;
            margin-bottom: 5pt;
            page-break-after: avoid;
        }
        .item-block {
            margin-bottom: 5.5pt;
            page-break-inside: avoid;
        }
        .item-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .item-title {
            font-size: 8pt;
            font-weight: bold;
            color: #1c1917;
            text-align: left;
            vertical-align: top;
        }
        .item-date {
            font-size: 7pt;
            font-weight: bold;
            color: #b45309;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
        }
        .item-sub {
            font-size: 7.5pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 1.5pt;
        }
        .item-desc {
            font-size: 7pt;
            color: #334155;
            text-align: justify;
            line-height: 1.3;
        }
        .skills-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4pt;
        }
        .skills-grid td {
            width: 50%;
            vertical-align: top;
            padding-right: 10pt;
            padding-bottom: 4pt;
        }
        .skill-bar-wrap {
            margin-bottom: 3pt;
        }
        .skill-bar-head {
            font-size: 7pt;
            font-weight: 600;
            color: #334155;
            margin-bottom: 1pt;
            width: 100%;
            display: table;
        }
        .skill-bar-name {
            display: table-cell;
            text-align: left;
        }
        .skill-bar-pct {
            display: table-cell;
            text-align: right;
            color: #b45309;
            font-weight: bold;
            font-size: 6.5pt;
        }
        .skill-track {
            width: 100%;
            height: 3pt;
            background-color: #e7e5e4;
            border-radius: 2pt;
            overflow: hidden;
        }
        .skill-fill {
            height: 3pt;
            background-color: #b45309;
            border-radius: 2pt;
        }
        .skill-pill {
            display: inline-block;
            background-color: #fffbeb;
            border: 0.5pt solid #fde68a;
            color: #92400e;
            padding: 1.5pt 5pt;
            border-radius: 3pt;
            font-size: 7pt;
            font-weight: 600;
            margin: 0 3pt 3pt 0;
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

    <!-- HEADER -->
    <div class="header-box">
        <table class="header-table">
            <tr>
                @if(!empty($data->photo))
                <td class="header-photo-td">
                    <img src="{{ $data->photo }}" class="header-photo" alt="Photo">
                </td>
                @endif
                <td style="vertical-align: top;">
                    <div class="name">{{ $getVal($data, "name") ?: "NAMA LENGKAP" }}</div>
                    <div class="job-title">{{ $getVal($data, "job_title") ?: "POSISI / PROFESI" }}</div>
                    <div class="contact-list">
                        @if(!empty($data->phone))<span class="contact-item">📞 {{ $data->phone }}</span>@endif
                        @if(!empty($data->email))<span class="contact-item">✉️ {{ $data->email }}</span>@endif
                        @if($getVal($data, "address", "location") !== "")<span class="contact-item">📍 {{ $getVal($data, "address", "location") }}</span>@endif
                        @if(!empty($data->linkedin))<span class="contact-item">🔗 {{ $data->linkedin }}</span>@endif
                        @if(!empty($data->website))<span class="contact-item">🌐 {{ $data->website }}</span>@endif
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- PROFIL RINGKAS -->
    @if($getVal($data, "profile", "summary") !== "")
    <div class="section-heading">Ringkasan Profil</div>
    <div class="item-desc" style="margin-bottom: 6pt;">
        {{ $getVal($data, "profile", "summary") }}
    </div>
    @endif

    <!-- KEAHLIAN / SKILLS -->
    @if(!empty($skills) && count($skills) > 0)
    <div class="section-heading">Keahlian & Kemampuan</div>
    <table class="skills-grid">
        <tr>
            <td style="width: 50%;">
                @foreach($skills as $idx => $s)
                    @php
                        $sName = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                        $sLvl  = is_array($s) ? ($s["level"] ?? "") : ($s->level ?? "");
                    @endphp
                    @if($idx % 2 == 0 && !empty($sName))
                        @if(!empty($sLvl) && is_numeric($sLvl) && $sLvl > 0)
                        <div class="skill-bar-wrap">
                            <div class="skill-bar-head">
                                <span class="skill-bar-name">{{ $sName }}</span>
                                <span class="skill-bar-pct">{{ $sLvl }}%</span>
                            </div>
                            <div class="skill-track"><div class="skill-fill" style="width: {{ $sLvl }}%;"></div></div>
                        </div>
                        @else
                        <span class="skill-pill">• {{ $sName }}</span>
                        @endif
                    @endif
                @endforeach
            </td>
            <td style="width: 50%;">
                @foreach($skills as $idx => $s)
                    @php
                        $sName = is_array($s) ? ($s["name"] ?? "") : ($s->name ?? "");
                        $sLvl  = is_array($s) ? ($s["level"] ?? "") : ($s->level ?? "");
                    @endphp
                    @if($idx % 2 != 0 && !empty($sName))
                        @if(!empty($sLvl) && is_numeric($sLvl) && $sLvl > 0)
                        <div class="skill-bar-wrap">
                            <div class="skill-bar-head">
                                <span class="skill-bar-name">{{ $sName }}</span>
                                <span class="skill-bar-pct">{{ $sLvl }}%</span>
                            </div>
                            <div class="skill-track"><div class="skill-fill" style="width: {{ $sLvl }}%;"></div></div>
                        </div>
                        @else
                        <span class="skill-pill">• {{ $sName }}</span>
                        @endif
                    @endif
                @endforeach
            </td>
        </tr>
    </table>
    @endif

    <!-- TOOLS & SOFTWARE -->
    @if(!empty($tools) && count($tools) > 0)
    <div class="section-heading">Tools & Software</div>
    <div style="margin-bottom: 5pt;">
        @foreach($tools as $tool)
            @php $tName = is_array($tool) ? ($tool["name"] ?? "") : ($tool->name ?? ""); @endphp
            @if(!empty($tName))<span class="skill-pill">{{ $tName }}</span>@endif
        @endforeach
    </div>
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
        @if(!empty($comp) || !empty($pos))
        <div class="item-block">
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $pos }}</td>
                    <td class="item-date">{{ $dateStr }}</td>
                </tr>
            </table>
            <div class="item-sub">{{ $comp }}</div>
            @if(!empty($desc))
            <div class="item-desc">{!! nl2br(e($desc)) !!}</div>
            @endif
        </div>
        @endif
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
        @if(!empty($inst))
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
        @endif
    @endforeach
    @endif

    <!-- PROYEK & PORTOFOLIO -->
    @if(!empty($projects) && count($projects) > 0)
    <div class="section-heading">Proyek & Portofolio</div>
    @foreach($projects as $proj)
        @php
            $pName = is_array($proj) ? ($proj["name"] ?? "") : ($proj->name ?? "");
            $pTech = is_array($proj) ? ($proj["technologies"] ?? "") : ($proj->technologies ?? "");
            $pLink = is_array($proj) ? ($proj["link"] ?? "") : ($proj->link ?? "");
            $pDesc = is_array($proj) ? ($proj["description"] ?? "") : ($proj->description ?? "");
        @endphp
        @if(!empty($pName))
        <div class="item-block">
            <div class="item-title">{{ $pName }} @if($pLink)<span style="font-weight: normal; font-size: 6.5pt; color: #b45309;">({{ $pLink }})</span>@endif</div>
            @if(!empty($pTech))<div class="item-sub">{{ $pTech }}</div>@endif
            @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
        </div>
        @endif
    @endforeach
    @endif

    <!-- PENGALAMAN MAGANG -->
    @if(!empty($internships) && count($internships) > 0)
    <div class="section-heading">Pengalaman Magang</div>
    @foreach($internships as $intern)
        @php
            $comp = is_array($intern) ? ($intern["company"] ?? "") : ($intern->company ?? "");
            $pos  = is_array($intern) ? ($intern["position"] ?? "") : ($intern->position ?? "");
            $start= is_array($intern) ? ($intern["start_year"] ?? "") : ($intern->start_year ?? "");
            $end  = is_array($intern) ? ($intern["end_year"] ?? ($intern["period"] ?? "")) : ($intern->end_year ?? ($intern->period ?? ""));
            $dateStr = $start ? ($start . " - " . ($end ?: "Sekarang")) : ($end ?: "");
            $desc = is_array($intern) ? ($intern["description"] ?? "") : ($intern->description ?? "");
        @endphp
        @if(!empty($comp) || !empty($pos))
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
        @endif
    @endforeach
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
        @if(!empty($cName))
        <div class="item-block">
            <div class="item-title">{{ $cName }}</div>
            <div class="item-sub">{{ $cIssuer }} @if($cYear)({{ $cYear }})@endif</div>
        </div>
        @endif
    @endforeach
    @endif

    <!-- ORGANISASI -->
    @if(!empty($organizations) && count($organizations) > 0)
    <div class="section-heading">Organisasi & Kepanitiaan</div>
    @foreach($organizations as $org)
        @php
            $orgName = is_array($org) ? ($org["organization_name"] ?? ($org["name"] ?? "")) : ($org->organization_name ?? ($org->name ?? ""));
            $role    = is_array($org) ? ($org["role"] ?? ($org["position"] ?? "")) : ($org->role ?? ($org->position ?? ""));
            $period  = is_array($org) ? ($org["period"] ?? "") : ($org->period ?? "");
            $desc    = is_array($org) ? ($org["description"] ?? "") : ($org->description ?? "");
        @endphp
        @if(!empty($orgName))
        <div class="item-block">
            <table class="item-table">
                <tr>
                    <td class="item-title">{{ $orgName }}</td>
                    <td class="item-date">{{ $period }}</td>
                </tr>
            </table>
            @if(!empty($role))<div class="item-sub">{{ $role }}</div>@endif
            @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
        </div>
        @endif
    @endforeach
    @endif

</body>
</html>