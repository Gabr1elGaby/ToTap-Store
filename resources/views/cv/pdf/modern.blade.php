<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $template->name ?? "Modern Minimalis Indonesia" }}</title>
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

        /* PAGE 1: 2-COLUMN WITH SIDEBAR */
        table.page1-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }
        td.sidebar-td {
            width: 32%;
            background-color: #0f172a;
            color: #cbd5e1;
            padding: 22pt 14pt 22pt 14pt;
            vertical-align: top;
        }
        td.main-td {
            width: 68%;
            background-color: #ffffff;
            padding: 22pt 28pt 22pt 18pt; /* Inset right padding */
            vertical-align: top;
        }

        /* SIDEBAR ELEMENTS */
        .photo-box {
            text-align: center;
            margin-bottom: 10pt;
            width: 100%;
        }
        .photo-img {
            width: 65pt;
            height: 65pt;
            border-radius: 50%;
            border: 2pt solid rgba(255,255,255,0.25);
        }
        .side-heading {
            font-size: 7.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #38bdf8;
            border-bottom: 1pt solid rgba(255,255,255,0.2);
            padding-bottom: 2pt;
            margin-top: 8pt;
            margin-bottom: 5pt;
        }
        .side-item {
            margin-bottom: 4pt;
            font-size: 6.8pt;
            line-height: 1.28;
        }
        .side-label {
            font-size: 6pt;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            margin-bottom: 1pt;
        }
        .side-value {
            color: #f1f5f9;
            word-wrap: break-word;
        }

        /* SKILL PROGRESS BARS */
        .skill-row {
            margin-bottom: 3.5pt;
        }
        .skill-name-row {
            font-size: 6.8pt;
            font-weight: bold;
            color: #e2e8f0;
            margin-bottom: 1pt;
        }
        .skill-bar-bg {
            width: 100%;
            height: 3.5pt;
            background-color: rgba(255,255,255,0.15);
            border-radius: 2pt;
            overflow: hidden;
        }
        .skill-bar-fill {
            height: 3.5pt;
            background-color: #38bdf8;
            border-radius: 2pt;
        }

        /* MAIN COLUMN ELEMENTS */
        .header-name {
            font-size: 15pt;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #0f172a;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .header-title {
            font-size: 8pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8pt;
        }
        .main-heading {
            font-size: 8pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #0f172a;
            border-bottom: 1.5pt solid #0284c7;
            padding-bottom: 2pt;
            margin-top: 8pt;
            margin-bottom: 5pt;
        }
        .item-block {
            margin-bottom: 5pt;
        }
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1pt;
        }
        .item-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #0f172a;
            text-align: left;
        }
        .item-date {
            font-size: 6.8pt;
            font-weight: bold;
            color: #64748b;
            text-align: right;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 7pt;
            font-weight: 600;
            color: #0284c7;
            margin-bottom: 1pt;
        }
        .item-desc {
            font-size: 6.8pt;
            color: #334155;
            text-align: justify; /* RATA KANAN KIRI */
            line-height: 1.32;
        }

        /* PAGE 2+: 100% FULL-WIDTH CONTINUATION VIA TABLE (NO MEPEK KANAN) */
        .page-break {
            page-break-before: always;
        }
        table.page2-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin: 0;
            padding: 0;
        }
        td.page2-td {
            padding: 24pt 30pt 24pt 30pt; /* Balanced 30pt margins left and right */
            vertical-align: top;
            background-color: #ffffff;
        }
    </style>
</head>
<body>
    @php
        $p = function($item, $key, $default = '') {
            if (is_object($item)) return $item->$key ?? $default;
            if (is_array($item)) return $item[$key] ?? $default;
            return $default;
        };

        $getVal = function($obj, ...$keys) use ($userData, $p) {
            $candidates = [$obj];
            if (isset($userData['cv'])) $candidates[] = $userData['cv'];
            if (isset($userData) && is_object($userData) && isset($userData->cv)) $candidates[] = $userData->cv;

            foreach ($candidates as $c) {
                if (!$c) continue;
                foreach ($keys as $k) {
                    $v = $p($c, $k);
                    if (!empty($v)) return $v;
                }
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

        $photoSrc = $getVal($data, "photo");
        if (empty($photoSrc) && !empty($userData['cv'])) {
            $photoSrc = $p($userData['cv'], 'photo');
        }

        // Determine whether Page 2 is needed
        $hasPage2 = (!empty($projects) && count($projects) > 0) ||
                    (!empty($internships) && count($internships) > 0) ||
                    (!empty($organizations) && count($organizations) > 0) ||
                    (count($experiences) > 2);
    @endphp

    <!-- PAGE 1: 2-COLUMN WITH SIDEBAR -->
    <table class="page1-table">
        <tr>
            <!-- SIDEBAR -->
            <td class="sidebar-td">
                @if(!empty($photoSrc))
                <div class="photo-box">
                    <img src="{{ $photoSrc }}" class="photo-img">
                </div>
                @endif

                <div class="side-heading" style="margin-top: 0;">Kontak Pribadi</div>
                @if(!empty($data->phone) || $getVal($data, "phone"))
                <div class="side-item">
                    <div class="side-label">TELEPON / WA</div>
                    <div class="side-value">{{ $getVal($data, "phone") }}</div>
                </div>
                @endif
                @if(!empty($data->email) || $getVal($data, "email"))
                <div class="side-item">
                    <div class="side-label">EMAIL</div>
                    <div class="side-value">{{ $getVal($data, "email") }}</div>
                </div>
                @endif
                @if($getVal($data, "address", "location") !== "")
                <div class="side-item">
                    <div class="side-label">DOMISILI</div>
                    <div class="side-value">{{ $getVal($data, "address", "location") }}</div>
                </div>
                @endif
                @if(!empty($data->linkedin) || $getVal($data, "linkedin"))
                <div class="side-item">
                    <div class="side-label">LINKEDIN</div>
                    <div class="side-value">{{ $getVal($data, "linkedin") }}</div>
                </div>
                @endif

                <!-- SKILLS -->
                @if(!empty($skills) && count($skills) > 0)
                <div class="side-heading">Keahlian & Tools</div>
                @foreach($skills as $sk)
                    @php
                        $sName = $p($sk, "name");
                        $sLvl  = $p($sk, "level", 80);
                        $sLvl  = is_numeric($sLvl) ? (int)$sLvl : 80;
                    @endphp
                    <div class="skill-row">
                        <div class="skill-name-row">{{ $sName }} ({{ $sLvl }}%)</div>
                        <div class="skill-bar-bg"><div class="skill-bar-fill" style="width: {{ $sLvl }}%;"></div></div>
                    </div>
                @endforeach
                @endif

                <!-- CERTIFICATES -->
                @if(!empty($certificates) && count($certificates) > 0)
                <div class="side-heading">Sertifikasi & Pelatihan</div>
                @foreach($certificates as $cert)
                    @php
                        $cName = $p($cert, "name");
                        $cIssuer = $p($cert, "issuer", $p($cert, "publisher"));
                        $cYear = $p($cert, "year");
                    @endphp
                    <div class="side-item">
                        <div class="side-value" style="font-weight: bold;">{{ $cName }}</div>
                        @if($cIssuer || $cYear)<div style="font-size: 6pt; color: #94a3b8;">{{ $cIssuer }} @if($cYear)({{ $cYear }})@endif</div>@endif
                    </div>
                @endforeach
                @endif
            </td>

            <!-- MAIN CONTENT -->
            <td class="main-td">
                <div class="header-name">{{ $getVal($data, "name") ?: "NAMA LENGKAP" }}</div>
                <div class="header-title">{{ $getVal($data, "job_title") ?: "POSISI / PEKERJAAN" }}</div>

                @if($getVal($data, "profile", "summary") !== "")
                <div class="main-heading" style="margin-top: 0;">Ringkasan Profil</div>
                <div class="item-desc" style="margin-bottom: 6pt;">
                    {{ $getVal($data, "profile", "summary") }}
                </div>
                @endif

                <!-- WORK EXPERIENCE (PAGE 1) -->
                @if(!empty($experiences) && count($experiences) > 0)
                <div class="main-heading">Pengalaman Kerja</div>
                @php
                    $expList = ($experiences instanceof \Illuminate\Support\Collection) ? $experiences->all() : (array)$experiences;
                    $expPage1 = $hasPage2 ? array_slice($expList, 0, 2) : $expList;
                    $expPage2 = $hasPage2 ? array_slice($expList, 2) : [];
                @endphp
                @foreach($expPage1 as $exp)
                    @php
                        $comp = $p($exp, "company");
                        $pos  = $p($exp, "position");
                        $start= $p($exp, "start_year");
                        $end  = $p($exp, "end_year");
                        $isCur= $p($exp, "is_current");
                        $dateStr = $start ? ($start . " - " . ($isCur ? "Sekarang" : ($end ?: "Sekarang"))) : ($end ?: "");
                        $desc = $p($exp, "description");
                    @endphp
                    <div class="item-block">
                        <table class="item-header-table">
                            <tr>
                                <td class="item-title">{{ $pos }}</td>
                                <td class="item-date">{{ $dateStr }}</td>
                            </tr>
                        </table>
                        <div class="item-subtitle">{{ $comp }}</div>
                        @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
                    </div>
                @endforeach
                @endif

                <!-- EDUCATION (PAGE 1) -->
                @if(!empty($educations) && count($educations) > 0)
                <div class="main-heading">Riwayat Pendidikan</div>
                @foreach($educations as $edu)
                    @php
                        $inst = $p($edu, "institution");
                        $deg  = $p($edu, "degree");
                        $maj  = $p($edu, "major", $p($edu, "field"));
                        $start= $p($edu, "start_year");
                        $end  = $p($edu, "end_year");
                        $dateStr = $start ? ($start . " - " . ($end ?: "Sekarang")) : ($end ?: "");
                        $sub  = trim($deg . ($deg && $maj ? " - " : "") . $maj);
                        $desc = $p($edu, "description");
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
            </td>
        </tr>
    </table>

    <!-- PAGE 2+: 100% FULL-WIDTH CONTINUATION VIA TABLE (NO NAME, NO MEPEK KANAN) -->
    @if($hasPage2)
    <div class="page-break"></div>
    <table class="page2-table">
        <tr>
            <td class="page2-td">
                <!-- REMAINING WORK EXPERIENCE (IF ANY) -->
                @if(!empty($expPage2) && count($expPage2) > 0)
                <div class="main-heading" style="margin-top: 0;">Pengalaman Kerja (Lanjutan)</div>
                @foreach($expPage2 as $exp)
                    @php
                        $comp = $p($exp, "company");
                        $pos  = $p($exp, "position");
                        $start= $p($exp, "start_year");
                        $end  = $p($exp, "end_year");
                        $isCur= $p($exp, "is_current");
                        $dateStr = $start ? ($start . " - " . ($isCur ? "Sekarang" : ($end ?: "Sekarang"))) : ($end ?: "");
                        $desc = $p($exp, "description");
                    @endphp
                    <div class="item-block">
                        <table class="item-header-table">
                            <tr>
                                <td class="item-title">{{ $pos }}</td>
                                <td class="item-date">{{ $dateStr }}</td>
                            </tr>
                        </table>
                        <div class="item-subtitle">{{ $comp }}</div>
                        @if(!empty($desc))<div class="item-desc">{!! nl2br(e($desc)) !!}</div>@endif
                    </div>
                @endforeach
                @endif

                <!-- PROJECTS & PORTFOLIO (FULL WIDTH 100%) -->
                @if(!empty($projects) && count($projects) > 0)
                <div class="main-heading" @if(empty($expPage2) || count($expPage2) == 0) style="margin-top: 0;" @endif>Proyek & Portofolio</div>
                @foreach($projects as $prj)
                    @php
                        $pName = $p($prj, "name");
                        $pRole = $p($prj, "role");
                        $pTech = $p($prj, "technologies");
                        $pLink = $p($prj, "link");
                        $pDesc = $p($prj, "description");
                        $pSub  = implode(" | ", array_filter([$pRole, $pTech, $pLink]));
                    @endphp
                    <div class="item-block">
                        <div class="item-title">{{ $pName }}</div>
                        @if(!empty($pSub))<div class="item-subtitle">{{ $pSub }}</div>@endif
                        @if(!empty($pDesc))<div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>@endif
                    </div>
                @endforeach
                @endif

                <!-- INTERNSHIPS (FULL WIDTH 100%) -->
                @if(!empty($internships) && count($internships) > 0)
                <div class="main-heading">Pengalaman Magang</div>
                @foreach($internships as $intern)
                    @php
                        $iComp = $p($intern, "company");
                        $iPos  = $p($intern, "position");
                        $iPer  = $p($intern, "period");
                        $iDesc = $p($intern, "description");
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

                <!-- ORGANIZATIONS (FULL WIDTH 100%) -->
                @if(!empty($organizations) && count($organizations) > 0)
                <div class="main-heading">Pengalaman Organisasi</div>
                @foreach($organizations as $org)
                    @php
                        $oName = $p($org, "organization_name");
                        $oRole = $p($org, "role");
                        $oPer  = $p($org, "period");
                        $oDesc = $p($org, "description");
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
            </td>
        </tr>
    </table>
    @endif
</body>
</html>