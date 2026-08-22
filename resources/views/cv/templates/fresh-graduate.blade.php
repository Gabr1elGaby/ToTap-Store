<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Fresh Graduate</title>
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
            color: #1e293b;
            line-height: 1.5;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .main-table { border-collapse: collapse; table-layout: fixed; width: 100%; height: 100%; min-height: 1123px; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        
        .left-col {
            width: 32%;
            background-color: #111827; /* Very dark grey/black */
            color: #e5e7eb;
            vertical-align: top;
            padding-bottom: 40px;
        }
        .right-col {
            width: 68%;
            background-color: #ffffff;
            padding: 40px 35px;
            vertical-align: top;
        }

        /* Photo Area */
        .photo-wrapper {
            padding: 40px 0;
            text-align: center;
            background-color: #f3f4f6;
            margin-bottom: 20px;
        }
        .photo {
            width: 130px; height: 130px;
            border-radius: 50%;
            border: 5px solid #ffffff;
            object-fit: cover;
            display: block;
            margin: 0 auto;
        }
        .photo-dark-bg {
            text-align: center;
            padding: 40px 0 20px 0;
        }

        /* Left Col Headers (Gold ribbon style) */
        .left-header {
            background-color: #D4AF37; /* Gold */
            color: #111827;
            font-size: 11pt;
            font-weight: bold;
            padding: 6px 20px;
            margin-bottom: 15px;
            margin-top: 25px;
            text-transform: uppercase;
            border-top-right-radius: 20px;
            border-bottom-right-radius: 20px;
            width: 80%;
            letter-spacing: 1px;
        }
        
        .left-content { padding: 0 20px; }
        
        .contact-item {
            margin-bottom: 12px;
            font-size: 9pt;
        }
        .contact-label {
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
            font-size: 8pt;
            display: block;
            margin-bottom: 2px;
        }
        .contact-value { color: #f3f4f6; }

        /* Right Col Elements */
        .name {
            font-size: 26pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }
        .job-title {
            font-size: 13pt;
            color: #4b5563;
            font-style: italic;
            margin-bottom: 30px;
        }
        .right-header {
            font-size: 13pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
            padding-bottom: 4px;
            margin-bottom: 15px;
            margin-top: 25px;
            letter-spacing: 1px;
        }
        
        .profile-text { text-align: justify; margin-bottom: 25px; }

        .item { margin-bottom: 18px; }
        .item-title-row { width: 100%; margin-bottom: 3px; }
        .item-title { font-weight: bold; font-size: 11pt; color: #111827; }
        .item-date { text-align: right; font-size: 9.5pt; color: #4b5563;  white-space: nowrap; }
        .item-subtitle { font-size: 10pt; color: #374151; font-weight: bold; margin-bottom: 4px; }
        .item-desc { font-size: 9.5pt; color: #4b5563; text-align: justify; }

        .skill-list, .lang-list { padding-left: 20px; margin: 0; }
        .skill-list li, .lang-list li { margin-bottom: 4px; font-size: 9.5pt; }

    </style>
</head>
<body>
    @php
        $hard_skills = collect(isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? $userData['cv']['skills'] : [])->filter(fn($s) => isset($s['level']) && $s['level'] !== '')->map(fn($s) => (object)$s)->all();
        $soft_skills = collect(isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? $userData['cv']['skills'] : [])->filter(fn($s) => !isset($s['level']) || $s['level'] === '')->map(fn($s) => (object)$s)->all();
        $data = isset($userData['cv']) && is_array($userData['cv']) ? (object)$userData['cv'] : (isset($userData['cv']) ? $userData['cv'] : (object)[]);
        $educations = isset($userData['cv']['educations']) && is_array($userData['cv']['educations']) ? collect($userData['cv']['educations'])->map(fn($i) => (object)$i) : [];
        $experiences = isset($userData['cv']['experiences']) && is_array($userData['cv']['experiences']) ? collect($userData['cv']['experiences'])->map(fn($i) => (object)$i) : [];
        $projects = isset($userData['cv']['projects']) && is_array($userData['cv']['projects']) ? collect($userData['cv']['projects'])->map(fn($i) => (object)$i) : [];
        $internships = isset($userData['cv']['internships']) && is_array($userData['cv']['internships']) ? collect($userData['cv']['internships'])->map(fn($i) => (object)$i) : [];
        $organizations = isset($userData['cv']['organizations']) && is_array($userData['cv']['organizations']) ? collect($userData['cv']['organizations'])->map(fn($i) => (object)$i) : [];
        $certificates = isset($userData['cv']['certificates']) && is_array($userData['cv']['certificates']) ? collect($userData['cv']['certificates'])->map(fn($i) => (object)$i) : [];
        $skills = isset($userData['cv']['skills']) && is_array($userData['cv']['skills']) ? collect($userData['cv']['skills'])->map(fn($i) => (object)$i) : [];
        
    @endphp

    <table class="main-table">
        <tr>
            <!-- LEFT COLUMN -->
            <td class="left-col">
                <div class="photo-dark-bg">
                    @if(!empty($data->photo))
                        <img src="{{ $data->photo ?? '' }}" class="photo">
                    @else
                        <!-- Placeholder if no photo -->
                        <div style="height: 100px;"></div>
                    @endif
                </div>

                <div class="left-header">Data Diri</div>
                <div class="left-content">
                    @if(!empty($data->phone))
                    <div class="contact-item">
                        <span class="contact-label">Telepon</span>
                        <span class="contact-value">{{ $data->phone ?? '' }}</span>
                    </div>
                    @endif
                    @if(!empty($data->email))
                    <div class="contact-item">
                        <span class="contact-label">Email</span>
                        <span class="contact-value">{{ $data->email ?? '' }}</span>
                    </div>
                    @endif
                    @if(!empty($data->address))
                    <div class="contact-item">
                        <span class="contact-label">Domisili</span>
                        <span class="contact-value">{{ $data->address ?? '' }}</span>
                    </div>
                    @endif
                </div>

                <div class="left-header">Media & Web</div>
                <div class="left-content">
                    @if(!empty($data->linkedin))
                    <div class="contact-item">
                        <span class="contact-label">LinkedIn</span>
                        <span class="contact-value">{{ $data->linkedin ?? '' }}</span>
                    </div>
                    @endif
                    @if(!empty($data->website))
                    <div class="contact-item">
                        <span class="contact-label">Website / Portfolio</span>
                        <span class="contact-value">{{ $data->website ?? '' }}</span>
                    </div>
                    @endif
                    @if(!empty($data->social_media))
                    <div class="contact-item">
                        <span class="contact-label">Social Media</span>
                        <span class="contact-value">{{ $data->social_media ?? '' }}</span>
                    </div>
                    @endif
                </div>
                
                
            </td>

            <!-- RIGHT COLUMN -->
            <td class="right-col">
                <div class="name">{{ $data->name ?? 'NAMA LENGKAP' }}</div>
                <div class="job-title">{{ $data->job_title ?? 'Lulusan Baru / Fresh Graduate' }}</div>
                
                @if(!empty($data->profile))
                <div class="right-header">Tentang Saya</div>
                <div class="profile-text">
                    {!! nl2br(e($data->profile)) !!}
                </div>
                @endif

                @if(count($educations) > 0)
                <div class="right-header">Pendidikan</div>
                @foreach($educations as $edu)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $edu->institution ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $edu->start_year ?? '' }} - {{ $edu->end_year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-desc">{{ $edu->degree ?? '' }}, {{ $edu->major ?? '' }}</div>
                </div>
                @endforeach
                @endif

                @if(count($internships) > 0)
                <div class="right-header">Pengalaman Magang</div>
                @foreach($internships as $int)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $int->position ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $int->start_year ?? '' }} - {{ $int->end_year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $int->company ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($int->description)) !!}</div>
                </div>
                @endforeach
                @endif
                
                @if(count($organizations) > 0)
                <div class="right-header">Pengalaman Organisasi</div>
                @foreach($organizations as $org)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $org->role ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $org->period ?? $org->year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $org->organization_name ?? $org->name ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($org->description)) !!}</div>
                </div>
                @endforeach
                @endif

                @if(count($projects) > 0)
                <div class="right-header">Project</div>
                @foreach($projects as $proj)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $proj->name ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $proj->year ?? $proj->link ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-subtitle">{{ $proj->role ?? $proj->technologies ?? '' }}</div>
                    <div class="item-desc">{!! nl2br(e($proj->description)) !!}</div>
                </div>
                @endforeach
                @endif

                                  @if(count($hard_skills) > 0)
                  <div class="right-header">Hard Skills</div>
                  <div style="padding-left: 0; margin-bottom: 20px;">
                      @foreach($hard_skills as $skill)
                      <div style="margin-bottom: 10px;">
                          <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 3px;">
                              <tr>
                                  <td style="font-size: 10pt; font-weight: bold; color: #111827;">{{ $skill->name ?? '' }}</td>
                                  <td align="right" style="font-size: 9pt; color: #D4AF37; font-weight: bold;">{{ $skill->level ?? '' }}%</td>
                              </tr>
                          </table>
                          <div style="width: 100%; background-color: #f3f4f6; height: 5px; border-radius: 3px;">
                              <div style="width: {{ $skill->level ?? '' }}%; background-color: #D4AF37; height: 100%; border-radius: 3px;"></div>
                          </div>
                      </div>
                      @endforeach
                  </div>
                  @endif

                  @if(count($soft_skills) > 0)
                  <div class="right-header">Soft Skills</div>
                  <ul class="skill-list" style="padding-left: 20px; margin-bottom: 20px;">
                      @foreach($soft_skills as $skill)
                      <li>{{ $skill->name ?? '' }}</li>
                      @endforeach
                  </ul>
                  @endif

                @if(count($certificates) > 0)
                <div class="right-header">Sertifikat</div>
                @foreach($certificates as $cert)
                <div class="item">
                    <table class="item-title-row">
                        <tr>
                            <td class="item-title" style="width: 75%;">{{ $cert->name ?? '' }}</td>
                            <td class="item-date" style="width: 25%;">{{ $cert->year ?? '' }}</td>
                        </tr>
                    </table>
                    <div class="item-desc">{{ $cert->issuer ?? $cert->publisher ?? '' }}</div>
                </div>
                @endforeach
                @endif

            </td>
        </tr>
    </table>
</body>
</html>
