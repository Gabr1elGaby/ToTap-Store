<?php
// We extract the variables just like in creative.blade.php
$data = isset($userData['cv']) ? (object)$userData['cv'] : (object)[];

$educations = isset($userData['cv']['educations']) && is_array($userData['cv']['educations']) ? collect($userData['cv']['educations'])->map(fn($i) => (object)$i) : collect([]);
$experiences = isset($userData['cv']['experiences']) && is_array($userData['cv']['experiences']) ? collect($userData['cv']['experiences'])->map(fn($i) => (object)$i) : collect([]);
$internships = isset($userData['cv']['internships']) && is_array($userData['cv']['internships']) ? collect($userData['cv']['internships'])->map(fn($i) => (object)$i) : collect([]);
$organizations = isset($userData['cv']['organizations']) && is_array($userData['cv']['organizations']) ? collect($userData['cv']['organizations'])->map(fn($i) => (object)$i) : collect([]);
$projects = isset($userData['cv']['projects']) && is_array($userData['cv']['projects']) ? collect($userData['cv']['projects'])->map(fn($i) => (object)$i) : collect([]);
$certificates = isset($userData['cv']['certificates']) && is_array($userData['cv']['certificates']) ? collect($userData['cv']['certificates'])->map(fn($i) => (object)$i) : collect([]);
$skills = isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? collect($userData['cv']['skills'])->map(fn($i) => (object)$i) : collect([]);

$initials = '';
if (!empty($data->name)) {
    $words = explode(' ', $data->name);
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Elegant</title>
    <style>
        @page { margin: 0px; }
        html, body { height: 100%; margin: 0; padding: 0; }
        body {
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0; padding: 0;
            font-size: 10pt;
            background-color: #ffffff;
            color: #333333;
            line-height: 1.5;
        }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            height: 100%;
            min-height: 1123px;
        }
        
        .header {
            background-color: #264653;
            color: #ffffff;
            padding: 40px 40px 20px 40px;
            border-bottom: 15px solid #d4af37;
        }
        
        .photo-wrapper {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid #d4af37;
            background-color: #ffffff;
            overflow: hidden;
            display: inline-block;
            text-align: center;
            line-height: 140px;
            font-size: 36pt;
            font-weight: bold;
            color: #264653;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #d4af37;
            margin: 0 0 4px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .job-title {
            font-size: 11pt;
            font-weight: bold;
            color: #ffffff;
            margin: 0 0 10px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .summary {
            font-size: 10.5pt;
            color: #e2e8f0;
            text-align: justify;
            line-height: 1.6;
        }
        
        .left-col {
            width: 35%;
            background-color: #264653;
            color: #ffffff;
            padding: 40px 30px;
            vertical-align: top;
        }
        
        .right-col {
            width: 65%;
            background-color: #ffffff;
            color: #333333;
            padding: 40px 40px;
            vertical-align: top;
        }
        
        .left-heading {
            border: 2px solid #d4af37;
            border-radius: 20px;
            text-align: center;
            color: #ffffff;
            font-weight: bold;
            font-size: 12pt;
            padding: 8px 15px;
            margin-bottom: 20px;
            margin-top: 30px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .right-heading {
            background-color: #c19a6b;
            border-radius: 20px;
            text-align: center;
            color: #ffffff;
            font-weight: bold;
            font-size: 13pt;
            padding: 8px 15px;
            margin-bottom: 20px;
            margin-top: 30px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        
        .left-heading:first-child, .right-heading:first-child {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 15px;
            font-size: 10pt;
            color: #e2e8f0;
            word-break: break-all;
        }
        
        .skill-item {
            margin-bottom: 8px;
            font-size: 10.5pt;
            color: #e2e8f0;
        }
        
        .skill-item::before {
            content: "■";
            color: #d4af37;
            font-size: 8pt;
            margin-right: 10px;
        }
        
        .item-block {
            margin-bottom: 20px;
        }
        .item-title {
            font-size: 12pt;
            font-weight: bold;
            color: #264653;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }
        .item-meta {
            font-size: 10.5pt;
            color: #333333;
            margin-bottom: 8px;
        }
        .item-desc {
            font-size: 10.5pt;
            color: #4b5563;
            text-align: justify;
        }
    </style>
</head>
<body>
    <table class="main-table" cellpadding="0" cellspacing="0">
        <!-- HEADER ROW -->
        <tr>
            <td colspan="2" class="header">
                <table width="100%" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="180" align="center" valign="top">
                            <div class="photo-wrapper">
                                @if(!empty($data->photo))
                                <img src="{{ $data->photo ?? '' }}">
                                @else
                                {{ $initials }}
                                @endif
                            </div>
                        </td>
                        <td valign="top" style="padding-left: 20px;">
                            <div class="name">{{ $data->name ?? '' }}</div>
                            <div class="job-title">{{ $data->job_title ?? '' }}</div>
                            <div class="summary">{{ $data->summary ?? '' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        
        <!-- CONTENT ROW -->
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                <div class="contact-item">
                    <strong>TELEPON</strong><br>
                    {{ $data->phone ?? '' }}
                </div>
                <div class="contact-item">
                    <strong>EMAIL</strong><br>
                    {{ $data->email ?? '' }}
                </div>
                <div class="contact-item">
                    <strong>DOMISILI</strong><br>
                    {{ $data->location ?? '' }}
                </div>
                @if(!empty($data->linkedin))
                <div class="contact-item">
                    <strong>LINKEDIN</strong><br>
                    {{ $data->linkedin ?? '' }}
                </div>
                @endif
                @if(!empty($data->website))
                <div class="contact-item">
                    <strong>WEBSITE / PORTOFOLIO</strong><br>
                    {{ $data->website ?? '' }}
                </div>
                @endif
                
                @if(count($skills) > 0)
                <div class="left-heading">Keahlian</div>
                @foreach($skills as $skill)
                <div class="skill-item">{{ $skill->name ?? '' }}</div>
                @endforeach
                @endif
            </td>
            
            <!-- RIGHT COLUMN -->
            <td class="right-col">
                @if(count($experiences) > 0)
                <div class="right-heading">Pengalaman Kerja</div>
                @foreach($experiences as $exp)
                <div class="item-block">
                    <div class="item-title">{{ $exp->position ?? '' }}</div>
                    <div class="item-meta">{{ $exp->company ?? '' }}  |  {{ $exp->start_year ?? '' }} - {{ isset($exp->is_current) && $exp->is_current ? 'Sekarang' : ($exp->end_year ?? '') }}</div>
                    <div class="item-desc">{!! nl2br(e($exp->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($internships) > 0)
                <div class="right-heading">Pengalaman Magang</div>
                @foreach($internships as $int)
                <div class="item-block">
                    <div class="item-title">{{ $int->position ?? '' }}</div>
                    <div class="item-meta">{{ $int->company ?? '' }}  |  {{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($int->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($educations) > 0)
                <div class="right-heading">Riwayat Pendidikan</div>
                @foreach($educations as $edu)
                <div class="item-block">
                    <div class="item-title">{{ $edu->degree ?? '' }} {{ $edu->field ?? '' }}</div>
                    <div class="item-meta">{{ $edu->institution ?? '' }}  |  {{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($edu->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($organizations) > 0)
                <div class="right-heading">Pengalaman Organisasi & Relawan</div>
                @foreach($organizations as $org)
                <div class="item-block">
                    <div class="item-title">{{ $org->role ?? '' }}</div>
                    <div class="item-meta">{{ $org->organization_name ?? '' }}  |  {{ $org->period ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($org->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($projects) > 0)
                <div class="right-heading">Proyek & Portofolio</div>
                @foreach($projects as $proj)
                <div class="item-block">
                    <div class="item-title">{{ $proj->name ?? '' }}</div>
                    <div class="item-meta">{{ $proj->technologies ?? '' }}  |  {{ $proj->link ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($proj->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($certificates) > 0)
                <div class="right-heading">Sertifikasi & Pelatihan</div>
                @foreach($certificates as $cert)
                <div class="item-block">
                    <div class="item-title">{{ $cert->name ?? '' }}</div>
                    <div class="item-meta">{{ $cert->issuer ?? '' }}  |  {{ $cert->year ?? '' }}</div>
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
