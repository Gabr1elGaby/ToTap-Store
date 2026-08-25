<?php
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
    <title>CV Modern</title>
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
        
        .left-col {
            width: 35%;
            background-color: #1d2b38;
            color: #ffffff;
            vertical-align: top;
            padding-bottom: 40px;
        }
        
        .right-col {
            width: 65%;
            background-color: #ffffff;
            color: #333333;
            padding: 50px 40px;
            vertical-align: top;
        }
        
        .photo-container {
            padding: 50px 0 30px 0;
            text-align: center;
        }
        
        .photo-wrapper {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #d1d5db; /* Light gray border */
            background-color: #ffffff;
            overflow: hidden;
            display: inline-block;
            text-align: center;
            line-height: 150px;
            font-size: 36pt;
            font-weight: bold;
            color: #1d2b38;
        }
        .photo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* Left Column White Blocks */
        .left-block {
            background-color: #ffffff;
            color: #1d2b38;
            border-radius: 0 30px 30px 0;
            margin-right: 25px; /* So it doesn't touch the right column */
            margin-bottom: 25px;
            padding: 20px 25px;
        }
        
        .left-heading-container {
            text-align: right; /* Aligned right inside the white block */
            margin-bottom: 15px;
        }
        
        .left-heading {
            background-color: #1d2b38;
            color: #ffffff;
            font-weight: bold;
            font-size: 11pt;
            padding: 6px 18px;
            border-radius: 20px;
            display: inline-block;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        .contact-item {
            margin-bottom: 10px;
            font-size: 9.5pt;
            color: #1d2b38;
            word-break: break-all;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .contact-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }
        
        .skill-item {
            margin-bottom: 8px;
            font-size: 9.5pt;
            color: #1d2b38;
            text-align: right;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .skill-item:last-child {
            border-bottom: none;
        }
        
        /* Right Column Styles */
        .name {
            font-size: 24pt;
            font-weight: bold;
            color: #111827;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            line-height: 1.2;
        }
        .job-title {
            font-size: 10.5pt;
            color: #4b5563;
            margin: 0 0 12px 0;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        
        .header-line {
            border: 0;
            border-bottom: 3px solid #1d2b38;
            margin: 15px 0 35px 0;
        }
        
        .right-heading {
            font-size: 14pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
        }
        .right-heading-line {
            border: 0;
            border-bottom: 2px solid #1d2b38;
            margin: 0 0 15px 0;
            width: 100%;
        }
        
        .item-block {
            margin-bottom: 20px;
        }
        .item-meta {
            font-size: 10pt;
            color: #1d2b38;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .item-title {
            font-size: 11.5pt;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        .item-subtitle {
            font-size: 10pt;
            color: #4b5563;
            margin-bottom: 6px;
        }
        .item-desc {
            font-size: 10pt;
            color: #4b5563;
            text-align: justify;
        }
        .summary {
            font-size: 10pt;
            color: #4b5563;
            text-align: justify;
            margin-bottom: 35px;
        }
        
    </style>
</head>
<body>
    <table class="main-table" cellpadding="0" cellspacing="0">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                <div class="photo-container">
                    <div class="photo-wrapper">
                        @if(!empty($data->photo))
                        <img src="{{ $data->photo ?? '' }}">
                        @else
                        {{ $initials }}
                        @endif
                    </div>
                </div>
                
                <!-- CONTACT BLOCK -->
                <div class="left-block">
                    <div class="left-heading-container">
                        <span class="left-heading">Kontak</span>
                    </div>
                    @if(!empty($data->phone))
                    <div class="contact-item">
                        {{ $data->phone }}<br><strong>Telepon / WA</strong>
                    </div>
                    @endif
                    @if(!empty($data->email))
                    <div class="contact-item">
                        {{ $data->email }}<br><strong>Email</strong>
                    </div>
                    @endif
                    @if(!empty($data->address ?? $data->location))
                    <div class="contact-item">
                        {{ $data->address ?? $data->location }}<br><strong>Domisili</strong>
                    </div>
                    @endif
                    @if(!empty($data->linkedin))
                    <div class="contact-item">
                        {{ $data->linkedin }}<br><strong>LinkedIn</strong>
                    </div>
                    @endif
                    @if(!empty($data->website))
                    <div class="contact-item">
                        {{ $data->website }}<br><strong>Website / Portofolio</strong>
                    </div>
                    @endif
                </div>
                
                <!-- SKILLS BLOCK -->
                @if(count($skills) > 0)
                <div class="left-block">
                    <div class="left-heading-container">
                        <span class="left-heading">Keahlian</span>
                    </div>
                    @foreach($skills as $skill)
                    <div class="skill-item">
                        <strong>{{ $skill->name ?? '' }}</strong>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- CERTIFICATES BLOCK -->
                @if(count($certificates) > 0)
                <div class="left-block">
                    <div class="left-heading-container">
                        <span class="left-heading">Sertifikasi</span>
                    </div>
                    @foreach($certificates as $cert)
                    <div class="skill-item">
                        <strong>{{ $cert->name ?? '' }}</strong><br>
                        {{ $cert->year ?? '' }}
                    </div>
                    @endforeach
                </div>
                @endif
            </td>
            
            <!-- RIGHT COLUMN -->
            <td class="right-col">
                <div class="name">{{ $data->name ?? '' }}</div>
                <div class="job-title">{{ $data->job_title ?? '' }}</div>
                <hr class="header-line">
                
                @if(!empty($data->profile))
                <div class="right-heading">Tentang Saya</div>
                <hr class="right-heading-line">
                <div class="summary">{!! nl2br(e($data->profile ?? '')) !!}</div>
                @endif
                
                @if(count($educations) > 0)
                <div class="right-heading">Riwayat Pendidikan</div>
                <hr class="right-heading-line">
                @foreach($educations as $edu)
                <div class="item-block">
                    <div class="item-meta">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</div>
                    <div class="item-title">{{ $edu->degree ?? '' }}{{ !empty($edu->major ?? $edu->field) ? (!empty($edu->degree) ? ' - ' : '') . ($edu->major ?? $edu->field) : '' }}</div>
                    <div class="item-subtitle">{{ $edu->institution ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($edu->description ?? '')) !!}</div>
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($experiences) > 0)
                <div class="right-heading">Pengalaman Kerja</div>
                <hr class="right-heading-line">
                @foreach($experiences as $exp)
                <div class="item-block">
                    <div class="item-meta">{{ $exp->start_year ?? '' }} - {{ isset($exp->is_current) && $exp->is_current ? 'Sekarang' : ($exp->end_year ?? '') }}</div>
                    <div class="item-title">{{ $exp->position ?? '' }}</div>
                    <div class="item-subtitle">{{ $exp->company ?? '' }}{{ !empty($exp->location) ? ' | ' . $exp->location : '' }}</div>
                    <div class="item-desc">{!! nl2br(e($exp->description ?? '')) !!}</div>
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($internships) > 0)
                <div class="right-heading">Pengalaman Magang</div>
                <hr class="right-heading-line">
                @foreach($internships as $int)
                <div class="item-block">
                    <div class="item-meta">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</div>
                    <div class="item-title">{{ $int->position ?? '' }}</div>
                    <div class="item-subtitle">{{ $int->company ?? '' }}{{ !empty($int->location) ? ' | ' . $int->location : '' }}</div>
                    <div class="item-desc">{!! nl2br(e($int->description ?? '')) !!}</div>
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($projects) > 0)
                <div class="right-heading">Proyek & Portofolio</div>
                <hr class="right-heading-line">
                @foreach($projects as $proj)
                <div class="item-block">
                    @if(!empty($proj->year))
                    <div class="item-meta">{{ $proj->year }}</div>
                    @endif
                    <div class="item-title">{{ $proj->name ?? '' }}</div>
                    @php
                        $projSub = array_filter([$proj->role ?? '', $proj->technologies ?? '', $proj->link ?? '']);
                    @endphp
                    @if(!empty($projSub))
                    <div class="item-subtitle">{{ implode(' | ', $projSub) }}</div>
                    @endif
                    @if(!empty($proj->description))
                    <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
                    @endif
                </div>
                @endforeach
                <div style="height: 15px;"></div>
                @endif
                
                @if(count($organizations) > 0)
                <div class="right-heading">Pengalaman Organisasi</div>
                <hr class="right-heading-line">
                @foreach($organizations as $org)
                <div class="item-block">
                    <div class="item-meta">{{ $org->period ?? '' }}</div>
                    <div class="item-title">{{ $org->role ?? '' }}</div>
                    <div class="item-subtitle">{{ $org->organization_name ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($org->description ?? '')) !!}</div>
                </div>
                @endforeach
                @endif
                
            </td>
        </tr>
    </table>
</body>
</html>
