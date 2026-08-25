<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "ATS Friendly Indonesia" }}</title>
    <style>
        @page {
            size: A4 portrait;
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
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 7.2pt;
            line-height: 1.32;
            background-color: #ffffff;
        }

        /* 100% FULL-HEIGHT SIDEBAR BACKGROUND */
        .sidebar-bg {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            width: 32%;
            background-color: #0f172a;
            z-index: -1000;
        }

        table.cv-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        td.sidebar-td {
            width: 32%;
            color: #cbd5e1;
            padding: 18pt 12pt 18pt 12pt;
            vertical-align: top;
        }
        td.content-td {
            width: 68%;
            background-color: #ffffff;
            padding: 18pt 18pt 18pt 15pt;
            vertical-align: top;
        }

        /* SIDEBAR ELEMENTS (CONTINUOUS STREAM) */
        .photo-container {
            text-align: center;
            margin-bottom: 8pt;
            width: 100%;
        }
        .photo {
            width: 58pt;
            height: 58pt;
            border-radius: 50%;
            border: 2pt solid #38bdf8;
            display: inline-block;
        }
        .sidebar-heading {
            font-size: 7.2pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #38bdf8;
            border-bottom: 1pt solid #334155;
            padding-bottom: 1.5pt;
            margin-top: 6.5pt;
            margin-bottom: 3pt;
        }
        .contact-item {
            margin-bottom: 2.5pt;
            line-height: 1.2;
        }
        .contact-label {
            font-size: 5.2pt;
            text-transform: uppercase;
            color: #38bdf8;
            font-weight: bold;
            display: block;
        }
        .contact-val {
            font-size: 6.8pt;
            color: #ffffff;
            word-wrap: break-word;
        }

        /* SKILL PROGRESS BARS */
        .skill-bar-item {
            margin-bottom: 3pt;
        }
        .skill-bar-header {
            font-size: 6.2pt;
            color: #cbd5e1;
            font-weight: 600;
            margin-bottom: 1pt;
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
            color: #38bdf8;
            font-weight: bold;
            font-size: 5.8pt;
        }
        .skill-track {
            width: 100%;
            height: 2.8pt;
            background-color: #334155;
            border-radius: 2pt;
            overflow: hidden;
        }
        .skill-fill {
            height: 2.8pt;
            background-color: #38bdf8;
            border-radius: 2pt;
        }
        .skill-bullet-item {
            font-size: 6.2pt;
            color: #cbd5e1;
            margin-bottom: 1.8pt;
            line-height: 1.2;
        }

        .cert-item {
            margin-bottom: 3pt;
            line-height: 1.2;
        }
        .cert-title {
            font-size: 6.2pt;
            font-weight: bold;
            color: #ffffff;
        }
        .cert-issuer {
            font-size: 5.8pt;
            color: #cbd5e1;
        }
        .cert-year {
            font-size: 5.8pt;
            color: #94a3b8;
        }

        /* RIGHT MAIN CONTENT (CONTINUOUS STREAM) */
        .name {
            font-size: 14pt;
            font-weight: 800;
            color: #0f172a;
            text-transform: uppercase;
            line-height: 1.15;
            margin-bottom: 1.5pt;
        }
        .job-title {
            font-size: 7.2pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 3.5pt;
        }
        .header-line {
            border: 0;
            border-top: 1.5pt solid #0284c7;
            margin: 0 0 4.5pt 0;
        }
        .summary {
            font-size: 6.8pt;
            color: #334155;
            text-align: justify;
            line-height: 1.28;
            margin-bottom: 5pt;
        }
        .right-heading {
            font-size: 7.2pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 1.5pt;
            margin-top: 5pt;
            margin-bottom: 3pt;
        }
        .item-block {
            margin-bottom: 3.5pt;
        }
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .item-title {
            font-size: 7.2pt;
            font-weight: bold;
            color: #0f172a;
            text-align: left;
            vertical-align: top;
        }
        .item-date {
            font-size: 6.2pt;
            font-weight: bold;
            color: #0284c7;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 6.8pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 1pt;
        }
        .item-desc {
            font-size: 6.2pt;
            color: #334155;
            line-height: 1.22;
            text-align: justify;
        }
    </style>
</head>
<body>
    <!-- FIXED 100% FULL-HEIGHT SIDEBAR BG -->
    <div class="sidebar-bg"></div>

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

    <table class="cv-table" cellpadding="0" cellspacing="0">
        <colgroup>
            <col style="width: 32%;">
            <col style="width: 68%;">
        </colgroup>
        <tr>
            <!-- SIDEBAR: CONTINUOUS STREAM (Photo -> Kontak -> Keahlian -> Tools -> Sertifikasi) -->
            <td class="sidebar-td">
                @if(!empty($data->photo))
                <div class="photo-container">
                    <img src="{{ $data->photo }}" class="photo" alt="Photo">
                </div>
                @endif

                <div class="sidebar-heading" style="margin-top: 0;">Kontak</div>
                @if(!empty($data->phone))
                <div class="contact-item">
                    <span class="contact-label">Telepon / WA</span>
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
                    <span class="contact-label">Domisili</span>
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
                    <span class="contact-label">Website / Portofolio</span>
                    <span class="contact-val">{{ $data->website }}</span>
                </div>
                @endif

                <!-- KEAHLIAN / SKILLS (LANGSUNG DI BAWAH KONTAK TANPA JARAK KOSONG) -->
                @if(!empty($skills) && count($skills) > 0)
                <div class="sidebar-heading">Keahlian</div>
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
                            <div class="skill-track"><div class="skill-fill" style="width: {{ $sLvl }}%;"></div></div>
                        </div>
                        @else
                        <div class="skill-bullet-item">• {{ $sName }}</div>
                        @endif
                    @endif
                @endforeach
                @endif

                <!-- TOOLS & SOFTWARE -->
                @if(!empty($tools) && count($tools) > 0)
                <div class="sidebar-heading">Tools & Software</div>
                @foreach($tools as $tool)
                    @php $tName = is_array($tool) ? ($tool["name"] ?? "") : ($tool->name ?? ""); @endphp
                    @if(!empty($tName))
                    <div class="skill-bullet-item">• {{ $tName }}</div>
                    @endif
                @endforeach
                @endif

                <!-- SERTIFIKASI -->
                @if(!empty($certificates) && count($certificates) > 0)
                <div class="sidebar-heading">Sertifikasi</div>
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

            <!-- MAIN CONTENT: CONTINUOUS STREAM (Name -> Job -> Summary -> Exp -> Edu -> Proj -> Intern -> Org) -->
            <td class="content-td">
                <!-- NAMA & POSISI -->
                <div class="name">{{ $getVal($data, "name") ?: "NAMA LENGKAP" }}</div>
                <div class="job-title">{{ $getVal($data, "job_title") ?: "POSISI / PROFESI" }}</div>
                <hr class="header-line">

                <!-- PROFIL RINGKAS -->
                @if($getVal($data, "profile", "summary") !== "")
                <div class="summary">
                    {{ $getVal($data, "profile", "summary") }}
                </div>
                @endif

                <!-- PENGALAMAN KERJA -->
                @if(!empty($experiences) && count($experiences) > 0)
                <div class="right-heading">Pengalaman Kerja</div>
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

                <!-- RIWAYAT PENDIDIKAN -->
                @if(!empty($educations) && count($educations) > 0)
                <div class="right-heading">Riwayat Pendidikan</div>
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

                <!-- PROYEK & PORTOFOLIO -->
                @if(!empty($projects) && count($projects) > 0)
                <div class="right-heading">Proyek & Portofolio</div>
                @foreach($projects as $proj)
                    @php
                        $pName = is_array($proj) ? ($proj["name"] ?? "") : ($proj->name ?? "");
                        $pTech = is_array($proj) ? ($proj["technologies"] ?? "") : ($proj->technologies ?? "");
                        $pLink = is_array($proj) ? ($proj["link"] ?? "") : ($proj->link ?? "");
                        $pDesc = is_array($proj) ? ($proj["description"] ?? "") : ($proj->description ?? "");
                    @endphp
                    @if(!empty($pName))
                    <div class="item-block">
                        <div class="item-title">{{ $pName }} @if($pLink)<span style="font-weight: normal; font-size: 6.2pt; color: #0284c7;">({{ $pLink }})</span>@endif</div>
                        @if(!empty($pTech))<div class="item-subtitle" style="font-size: 6.2pt;">{{ $pTech }}</div>@endif
                        @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
                    </div>
                    @endif
                @endforeach
                @endif

                <!-- PENGALAMAN MAGANG -->
                @if(!empty($internships) && count($internships) > 0)
                <div class="right-heading">Pengalaman Magang</div>
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

                <!-- ORGANISASI & KEPANITIAAN -->
                @if(!empty($organizations) && count($organizations) > 0)
                <div class="right-heading">Organisasi & Kepanitiaan</div>
                @foreach($organizations as $org)
                    @php
                        $orgName = is_array($org) ? ($org["organization_name"] ?? ($org["name"] ?? "")) : ($org->organization_name ?? ($org->name ?? ""));
                        $role    = is_array($org) ? ($org["role"] ?? ($org["position"] ?? "")) : ($org->role ?? ($org->position ?? ""));
                        $period  = is_array($org) ? ($org["period"] ?? "") : ($org->period ?? "");
                        $desc    = is_array($org) ? ($org["description"] ?? "") : ($org->description ?? "");
                    @endphp
                    @if(!empty($orgName))
                    <div class="item-block">
                        <table class="item-header-table">
                            <tr>
                                <td class="item-title">{{ $orgName }}</td>
                                <td class="item-date">{{ $period }}</td>
                            </tr>
                        </table>
                        @if(!empty($role))<div class="item-subtitle">{{ $role }}</div>@endif
                        @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
                    </div>
                    @endif
                @endforeach
                @endif

            </td>
        </tr>
    </table>
</body>
</html>