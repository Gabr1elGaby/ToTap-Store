<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ !empty($data->name) ? $data->name : "Curriculum Vitae" }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        body {
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            font-size: 8.5pt;
            line-height: 1.35;
            color: #334155;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
        }

        /* PAGE 1: 2-COLUMN TABLE */
        .page1-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        
        /* SIDEBAR (32%) */
        .sidebar-td {
            width: 32%;
            background-color: #312e81;
            color: #fdf2f8;
            padding: 24pt 14pt 20pt 16pt;
            vertical-align: top;
        }
        
        /* MAIN CONTENT (68%) */
        .content-td {
            width: 68%;
            background-color: #ffffff;
            padding: 24pt 20pt 20pt 20pt;
            vertical-align: top;
        }

        /* SIDEBAR ELEMENTS */
        .photo-container {
            text-align: center;
            margin-bottom: 12pt;
        }
        .photo {
            width: 72pt;
            height: 72pt;
            border-radius: 50%;
            object-fit: cover;
            border: 2pt solid #f472b6;
        }
        .sidebar-heading {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #fbcfe8;
            border-bottom: 1pt solid #4338ca;
            padding-bottom: 2pt;
            margin-top: 10pt;
            margin-bottom: 6pt;
        }
        .contact-item {
            margin-bottom: 5pt;
            line-height: 1.25;
        }
        .contact-label {
            font-size: 6.5pt;
            text-transform: uppercase;
            color: #f472b6;
            font-weight: bold;
            display: block;
        }
        .contact-val {
            font-size: 7.5pt;
            color: #ffffff;
            word-wrap: break-word;
        }
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0 0 6pt 0;
        }
        .skill-list li {
            font-size: 7.5pt;
            color: #fdf2f8;
            margin-bottom: 3pt;
            line-height: 1.25;
        }
        .skill-list li::before {
            content: "• ";
            color: #f472b6;
            font-weight: bold;
        }
        .cert-item {
            margin-bottom: 5pt;
            line-height: 1.25;
        }
        .cert-title {
            font-size: 7.5pt;
            font-weight: bold;
            color: #ffffff;
        }
        .cert-year {
            font-size: 7pt;
            color: #fbcfe8;
        }

        /* MAIN CONTENT ELEMENTS */
        .name {
            font-size: 16pt;
            font-weight: 800;
            color: #1e1b4b;
            text-transform: uppercase;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #db2777;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6pt;
        }
        .header-line {
            border: 0;
            border-top: 1.5pt solid #f472b6;
            margin: 0 0 8pt 0;
        }
        .summary {
            font-size: 8pt;
            color: #334155;
            text-align: justify;
            line-height: 1.35;
            margin-bottom: 10pt;
        }
        .right-heading {
            font-size: 9pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #312e81;
            border-bottom: 1pt solid #cbd5e1;
            padding-bottom: 2pt;
            margin-top: 10pt;
            margin-bottom: 6pt;
        }
        .item-block {
            margin-bottom: 7pt;
        }
        .item-header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5pt;
        }
        .item-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #1e1b4b;
            text-align: left;
            vertical-align: top;
        }
        .item-date {
            font-size: 7.5pt;
            font-weight: bold;
            color: #db2777;
            text-align: right;
            vertical-align: top;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 8pt;
            font-weight: 600;
            color: #475569;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 7.5pt;
            color: #334155;
            line-height: 1.35;
            text-align: justify;
        }

        /* PAGE 2 CONTAINER */
        .page2-container {
            page-break-before: always;
            width: auto;
            background-color: #ffffff;
            position: relative;
            z-index: 10;
            padding: 26pt 32pt 20pt 32pt;
            margin: 0;
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

    <!-- 2-COLUMN CV TABLE -->
    <table class="page1-table" cellpadding="0" cellspacing="0" style="width: 100%; border-collapse: collapse;">
        <tr>
            <!-- SIDEBAR -->
            <td class="sidebar-td" style="width: 32%; background-color: #312e81; vertical-align: top; padding: 22pt 16pt;">
                @if(!empty($data->photo))
                <div class="photo-container">
                    <img src="{{ $data->photo }}" class="photo" alt="Photo">
                </div>
                @endif

                <div class="sidebar-heading">Kontak</div>
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
                @if(!empty($data->social_media))
                <div class="contact-item">
                    <span class="contact-label">Media Sosial</span>
                    <span class="contact-val">{{ $data->social_media }}</span>
                </div>
                @endif
                
                @if(count($skills) > 0)
                <div class="sidebar-heading">Keahlian</div>
                <div style="margin-bottom: 6pt;">
                    @foreach($skills as $skill)
                        @php
                            $lvl = null;
                            if (is_object($skill)) {
                                $sName = $skill->name ?? "";
                                $sLvl = $skill->level ?? null;
                            } elseif (is_array($skill)) {
                                $sName = $skill["name"] ?? "";
                                $sLvl = $skill["level"] ?? null;
                            } else {
                                $sName = (string) $skill;
                                $sLvl = null;
                            }
                            if ($sLvl !== null && is_numeric($sLvl) && $sLvl > 0) {
                                $lvl = (int) $sLvl;
                            } elseif ($sLvl !== null && preg_match("/(\d+)/", (string) $sLvl, $m)) {
                                $lvl = (int) $m[1];
                            }
                        @endphp
                        
                        @if($lvl !== null)
                        <div style="margin-bottom: 5pt; width: 100%;">
                            <table style="width: 100%; margin-bottom: 1.5pt; border-collapse: collapse;">
                                <tr>
                                    <td style="font-size: 7.5pt; font-weight: bold; color: #fdf2f8; text-align: left; vertical-align: middle; padding: 0;">
                                        {{ $sName }}
                                    </td>
                                    <td style="font-size: 7pt; font-weight: bold; color: #f472b6; text-align: right; width: 28pt; vertical-align: middle; padding: 0;">
                                        {{ $lvl }}%
                                    </td>
                                </tr>
                            </table>
                            <div style="width: 100%; height: 3.5pt; background-color: #4338ca; border-radius: 2pt; overflow: hidden;">
                                <div style="width: {{ $lvl }}%; height: 3.5pt; background-color: #f472b6; border-radius: 2pt;"></div>
                            </div>
                        </div>
                        @else
                        <div style="margin-bottom: 3pt; font-size: 7.5pt; color: #fdf2f8; line-height: 1.25;">
                            <span style="color: #f472b6; font-weight: bold;">•</span> {{ $sName }}
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif
                
                @if(count($tools) > 0)
                <div class="sidebar-heading">Tools & Software</div>
                <ul class="skill-list">
                    @foreach($tools as $tool)
                    <li>{{ is_object($tool) ? ($tool->name ?? "") : (is_array($tool) ? ($tool["name"] ?? "") : $tool) }}</li>
                    @endforeach
                </ul>
                @endif
                
                @if(count($certificates) > 0)
                <div class="sidebar-heading">Sertifikasi</div>
                @foreach($certificates as $cert)
                @php
                    $cName = is_object($cert) ? ($cert->name ?? "") : (is_array($cert) ? ($cert["name"] ?? "") : $cert);
                    $cYear = is_object($cert) ? ($cert->year ?? "") : (is_array($cert) ? ($cert["year"] ?? "") : "");
                @endphp
                <div class="cert-item">
                    <div class="cert-title">{{ $cName }}</div>
                    @if(!empty($cYear))
                    <div class="cert-year">{{ $cYear }}</div>
                    @endif
                </div>
                @endforeach
                @endif
            </td>

            <!-- MAIN CONTENT -->
            <td class="content-td" style="width: 68%; background-color: #ffffff; vertical-align: top; padding: 22pt 24pt 22pt 20pt;">
                <div class="name">{{ !empty($data->name) ? $data->name : "NAMA LENGKAP" }}</div>
                <div class="job-title">{{ !empty($data->job_title) ? $data->job_title : "POSISI / PEKERJAAN" }}</div>
                <hr class="header-line">
                
                @if(!empty($data->summary) || !empty($data->profile))
                <div class="summary">
                    {!! nl2br(e($data->summary ?: $data->profile)) !!}
                </div>
                @endif

                @if(count($experiences) > 0)
                <div class="right-heading">Pengalaman Kerja</div>
                @foreach($experiences as $exp)
                @php
                    $pos = is_object($exp) ? ($exp->position ?? "") : ($exp["position"] ?? "");
                    $sYr = is_object($exp) ? ($exp->start_year ?? "") : ($exp["start_year"] ?? "");
                    $isCur = is_object($exp) ? (!empty($exp->is_current)) : (!empty($exp["is_current"]));
                    $eYr = is_object($exp) ? ($exp->end_year ?? "") : ($exp["end_year"] ?? "");
                    $comp = is_object($exp) ? ($exp->company ?? "") : ($exp["company"] ?? "");
                    $loc = is_object($exp) ? ($exp->location ?? "") : ($exp["location"] ?? "");
                    $desc = is_object($exp) ? ($exp->description ?? "") : ($exp["description"] ?? "");
                @endphp
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $pos }}</td>
                            <td class="item-date" style="width: 30%;">{{ $sYr }} - {{ $isCur ? "Sekarang" : $eYr }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $comp }} @if(!empty($loc)) | {{ $loc }} @endif</div>
                    @if(!empty($desc))
                    <div class="item-desc">{!! nl2br(e($desc)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif

                @if(count($educations) > 0)
                <div class="right-heading">Riwayat Pendidikan</div>
                @foreach($educations as $edu)
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ is_object($edu) ? ($edu->institution ?? "") : ($edu["institution"] ?? "") }}</td>
                            <td class="item-date" style="width: 30%;">{{ is_object($edu) ? ($edu->start_year ?? "") : ($edu["start_year"] ?? "") }} - {{ is_object($edu) ? ($edu->end_year ?? "") : ($edu["end_year"] ?? "") }}</td>
                        </tr>
                    </table>
                    @php
                        $deg = is_object($edu) ? ($edu->degree ?? "") : ($edu["degree"] ?? "");
                        $maj = $getVal($edu, "major", "field");
                        $eduDesc = is_object($edu) ? ($edu->description ?? "") : ($edu["description"] ?? "");
                    @endphp
                    <div class="item-subtitle">{{ $deg }}{{ $maj !== "" ? ($deg !== "" ? " - " : "") . $maj : "" }}</div>
                    @if(!empty($eduDesc))
                    <div class="item-desc">{!! nl2br(e($eduDesc)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif

                @if(count($projects) > 0)
                <div class="right-heading">Proyek & Portofolio</div>
                @foreach($projects as $proj)
                @php
                    $pName = is_object($proj) ? ($proj->name ?? "") : ($proj["name"] ?? "");
                    $pYr = is_object($proj) ? ($proj->year ?? ($proj->link ?? "")) : ($proj["year"] ?? ($proj["link"] ?? ""));
                    $pRole = is_object($proj) ? ($proj->role ?? "") : ($proj["role"] ?? "");
                    $pTech = is_object($proj) ? ($proj->technologies ?? "") : ($proj["technologies"] ?? "");
                    $pLink = is_object($proj) ? ($proj->link ?? "") : ($proj["link"] ?? "");
                    $pDesc = is_object($proj) ? ($proj->description ?? "") : ($proj["description"] ?? "");
                    $projSub = array_filter([$pRole, $pTech, (!empty($pYr) ? $pLink : "")]);
                @endphp
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $pName }}</td>
                            <td class="item-date" style="width: 30%;">{{ $pYr }}</td>
                        </tr>
                    </table>
                    @if(!empty($projSub))
                    <div class="item-subtitle">{{ implode(" | ", $projSub) }}</div>
                    @endif
                    @if(!empty($pDesc))
                    <div class="item-desc">{!! nl2br(e($pDesc)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif

                @if(count($internships) > 0)
                <div class="right-heading">Pengalaman Magang</div>
                @foreach($internships as $int)
                @php
                    $iPos = is_object($int) ? ($int->position ?? "") : ($int["position"] ?? "");
                    $iPeriod = is_object($int) ? ($int->period ?? (($int->start_year ?? "") . " - " . ($int->end_year ?? ""))) : ($int["period"] ?? (($int["start_year"] ?? "") . " - " . ($int["end_year"] ?? "")));
                    $iComp = is_object($int) ? ($int->company ?? "") : ($int["company"] ?? "");
                    $iLoc = is_object($int) ? ($int->location ?? "") : ($int["location"] ?? "");
                    $iDesc = is_object($int) ? ($int->description ?? "") : ($int["description"] ?? "");
                @endphp
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $iPos }}</td>
                            <td class="item-date" style="width: 30%;">{{ $iPeriod }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $iComp }} @if(!empty($iLoc)) | {{ $iLoc }} @endif</div>
                    @if(!empty($iDesc))
                    <div class="item-desc">{!! nl2br(e($iDesc)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif

                @if(count($organizations) > 0)
                <div class="right-heading">Pengalaman Organisasi</div>
                @foreach($organizations as $org)
                @php
                    $oRole = is_object($org) ? ($org->role ?? "") : ($org["role"] ?? "");
                    $oPeriod = is_object($org) ? ($org->period ?? ($org->start_year ?? ($org->year ?? ""))) : ($org["period"] ?? ($org["start_year"] ?? ($org["year"] ?? "")));
                    $oName = is_object($org) ? ($org->organization_name ?? ($org->name ?? "")) : ($org["organization_name"] ?? ($org["name"] ?? ""));
                    $oDesc = is_object($org) ? ($org->description ?? "") : ($org["description"] ?? "");
                @endphp
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;">{{ $oRole }}</td>
                            <td class="item-date" style="width: 30%;">{{ $oPeriod }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $oName }}</div>
                    @if(!empty($oDesc))
                    <div class="item-desc">{!! nl2br(e($oDesc)) !!}</div>
                    @endif
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>