<?php
$uData = isset($userData) ? $userData : get_defined_vars();
$getCol = function($key) use ($uData) {
    if (isset($uData[$key]) && is_array($uData[$key])) {
        return array_map(function($i) { return is_array($i) ? (object)$i : $i; }, $uData[$key]);
    }
    if (isset($uData[$key]) && $uData[$key] instanceof \Illuminate\Support\Collection) {
        return $uData[$key]->map(function($i) { return is_array($i) ? (object)$i : $i; })->all();
    }
    return [];
};

$getVal = function($item, ...$keys) {
    foreach ($keys as $k) {
        if (is_object($item) && isset($item->$k) && !empty($item->$k)) return $item->$k;
        if (is_array($item) && isset($item[$k]) && !empty($item[$k])) return $item[$k];
    }
    return "";
};

$cvRaw = $uData["cv"] ?? ($cv ?? ($data ?? []));
$data = is_object($cvRaw) ? $cvRaw : (object)$cvRaw;

$educations    = $getCol("educations");
$experiences   = $getCol("experiences");
$skills        = $getCol("skills");
$tools         = $getCol("tools");
$certificates  = $getCol("certificates");
$projects      = $getCol("projects");
$internships   = $getCol("internships");
$organizations = $getCol("organizations");

$initials = "CV";
if (!empty($data->name)) {
    $words = explode(" ", trim($data->name));
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ""));
}

$hasPage2 = (count($experiences) > 0 || count($projects) > 0 || count($internships) > 0 || count($organizations) > 0);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Fresh Graduate</title>
    <style>
        @page {
            margin: 0;
            size: a4 portrait;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: "Helvetica", "Arial", sans-serif;
            font-size: 9.5pt;
            background-color: #ffffff;
            color: #334155;
            line-height: 1.45;
        }
        
        /* PAGE 1: 2-COLUMN PREMIUM TABLE */
        .page1-table {
            width: 100%;
            height: 842pt;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .sidebar-td {
            width: 32%;
            height: 842pt;
            background-color: #111827;
            color: #cbd5e1;
            padding: 35pt 16pt 30pt 18pt;
            vertical-align: top;
        }
        .content-td {
            width: 68%;
            height: 842pt;
            background-color: #ffffff;
            padding: 35pt 28pt 30pt 24pt;
            vertical-align: top;
        }
        
        .sidebar-heading {
            font-size: 10pt;
            font-weight: bold;
            color: #38bdf8;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1pt solid #334155;
            padding-bottom: 4pt;
            margin-top: 18pt;
            margin-bottom: 10pt;
        }
        .sidebar-heading:first-of-type {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 8pt;
            font-size: 8.5pt;
            line-height: 1.35;
        }
        .contact-label {
            display: block;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            margin-bottom: 1pt;
        }
        .contact-val {
            color: #f8fafc;
            word-break: break-word;
        }
        
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            font-size: 8.5pt;
            margin-bottom: 5pt;
            color: #f1f5f9;
        }
        
        .cert-item {
            margin-bottom: 8pt;
        }
        .cert-title {
            font-size: 8.5pt;
            font-weight: bold;
            color: #f8fafc;
            line-height: 1.25;
        }
        .cert-year {
            font-size: 7.5pt;
            color: #38bdf8;
        }

        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 10.5pt;
            font-weight: bold;
            color: #D4AF37;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 10pt;
        }
        .header-line {
            border: none;
            height: 1.5pt;
            background-color: #e2e8f0;
            margin-bottom: 12pt;
        }
        
        .summary {
            font-size: 9pt;
            color: #475569;
            text-align: justify;
            margin-bottom: 14pt;
            line-height: 1.45;
        }
        
        .right-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5pt solid #D4AF37;
            padding-bottom: 3pt;
            margin-top: 14pt;
            margin-bottom: 8pt;
        }
        .right-heading:first-of-type {
            margin-top: 0;
        }
        
        .item-block {
            margin-bottom: 10pt;
        }
        .item-header-table {
            width: 100%;
            margin-bottom: 2pt;
            border-collapse: collapse;
        }
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #111827;
        }
        .item-date {
            text-align: right;
            font-size: 8.5pt;
            color: #64748b;
            white-space: nowrap;
        }
        .item-subtitle {
            font-size: 9pt;
            font-weight: bold;
            color: #D4AF37;
            margin-bottom: 3pt;
        }
        .item-desc {
            font-size: 8.5pt;
            color: #475569;
            text-align: justify;
            line-height: 1.4;
        }
        
        /* PAGE 2+: FULL-WIDTH CONTINUATION CONTAINER */
        .page2-fullwidth {
            page-break-before: always;
            width: 100%;
            padding: 38pt 35pt 32pt 35pt;
            background-color: #ffffff;
        }
        .full-heading {
            font-size: 11.5pt;
            font-weight: bold;
            color: #111827;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5pt solid #D4AF37;
            padding-bottom: 3pt;
            margin-top: 16pt;
            margin-bottom: 10pt;
        }
        .full-heading:first-of-type {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <!-- PAGE 1: 2-COLUMN HEADER + CORE PROFILE + EDUCATION -->
    <table class="page1-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sidebar-td">
                <div style="text-align: center; margin-bottom: 16pt;">
                    <?php if(!empty($data->photo)): ?>
                        <img src="<?php echo $data->photo; ?>" style="width: 75pt; height: 75pt; border-radius: 50%; border: 3pt solid #38bdf8; display: block; margin: 0 auto; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 75pt; height: 75pt; border-radius: 50%; border: 3pt solid #38bdf8; background-color: #1e293b; margin: 0 auto; text-align: center; line-height: 75pt; font-size: 22pt; font-weight: bold; color: #38bdf8;">
                            <?php echo $initials; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-heading">Kontak</div>
                <?php if(!empty($data->phone)): ?>
                <div class="contact-item">
                    <span class="contact-label">Telepon / WA</span>
                    <span class="contact-val"><?php echo $data->phone; ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->email)): ?>
                <div class="contact-item">
                    <span class="contact-label">Email</span>
                    <span class="contact-val"><?php echo $data->email; ?></span>
                </div>
                <?php endif; ?>
                <?php if($getVal($data, "address", "location") !== ""): ?>
                <div class="contact-item">
                    <span class="contact-label">Domisili</span>
                    <span class="contact-val"><?php echo $getVal($data, "address", "location"); ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->linkedin)): ?>
                <div class="contact-item">
                    <span class="contact-label">LinkedIn</span>
                    <span class="contact-val"><?php echo $data->linkedin; ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->website)): ?>
                <div class="contact-item">
                    <span class="contact-label">Website / Portofolio</span>
                    <span class="contact-val"><?php echo $data->website; ?></span>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->social_media)): ?>
                <div class="contact-item">
                    <span class="contact-label">Media Sosial</span>
                    <span class="contact-val"><?php echo $data->social_media; ?></span>
                </div>
                <?php endif; ?>
                
                <?php if(count($skills) > 0): ?>
                <div class="sidebar-heading">Keahlian</div>
                <ul class="skill-list">
                    <?php foreach($skills as $skill): ?>
                    <li>• <?php echo $skill->name ?? ""; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <?php if(count($tools) > 0): ?>
                <div class="sidebar-heading">Tools & Software</div>
                <ul class="skill-list">
                    <?php foreach($tools as $tool): ?>
                    <li>• <?php echo $tool->name ?? ""; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
                
                <?php if(count($certificates) > 0): ?>
                <div class="sidebar-heading">Sertifikasi</div>
                <?php foreach($certificates as $cert): ?>
                <div class="cert-item">
                    <div class="cert-title"><?php echo $cert->name ?? ""; ?></div>
                    <?php if(!empty($cert->year)): ?>
                    <div class="cert-year"><?php echo $cert->year; ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </td>

            <td class="content-td">
                <div class="name"><?php echo !empty($data->name) ? $data->name : "NAMA LENGKAP"; ?></div>
                <div class="job-title"><?php echo !empty($data->job_title) ? $data->job_title : "POSISI / PEKERJAAN"; ?></div>
                <hr class="header-line">
                <?php
                    $summaryText = $getVal($data, "profile", "summary", "about");
                ?>
                <?php if($summaryText !== ""): ?>
                <div class="summary">
                    <?php echo nl2br(htmlspecialchars($summaryText)); ?>
                </div>
                <?php endif; ?>

                <?php if(count($educations) > 0): ?>
                <div class="right-heading">Riwayat Pendidikan</div>
                <?php foreach($educations as $edu): ?>
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;"><?php echo $edu->institution ?? ""; ?></td>
                            <td class="item-date" style="width: 30%;"><?php echo $edu->start_year ?? ""; ?> - <?php echo $edu->end_year ?? ""; ?></td>
                        </tr>
                    </table>
                    <?php
                        $deg = $edu->degree ?? "";
                        $maj = $getVal($edu, "major", "field");
                    ?>
                    <div class="item-subtitle"><?php echo $deg; ?><?php echo $maj !== "" ? ($deg !== "" ? " - " : "") . $maj : ""; ?></div>
                    <?php if(!empty($edu->description)): ?>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($edu->description)); ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php elseif(count($experiences) > 0): ?>
                <?php 
                    $firstExp = $experiences[0];
                ?>
                <div class="right-heading">Pengalaman Kerja Terakhir</div>
                <div class="item-block">
                    <table class="item-header-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="item-title" style="width: 70%;"><?php echo $firstExp->position ?? ""; ?></td>
                            <td class="item-date" style="width: 30%;"><?php echo $firstExp->start_year ?? ""; ?> - <?php echo $firstExp->is_current ? "Sekarang" : ($firstExp->end_year ?? ""); ?></td>
                        </tr>
                    </table>
                    <div class="item-subtitle"><?php echo $firstExp->company ?? ""; ?> <?php if(!empty($firstExp->location)): ?> | <?php echo $firstExp->location; ?> <?php endif; ?></div>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($firstExp->description ?? "")); ?></div>
                </div>
                <?php endif; ?>
            </td>
        </tr>
    </table>

    <?php if($hasPage2): ?>
    <!-- PAGE 2+: FULL-WIDTH CONTINUATION -->
    <div class="page2-fullwidth">
        <?php if(count($experiences) > 0 && count($educations) > 0): ?>
        <div class="full-heading">Pengalaman Kerja</div>
        <?php foreach($experiences as $exp): ?>
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%; font-size: 10pt;"><?php echo $exp->position ?? ""; ?></td>
                    <td class="item-date" style="width: 25%;"><?php echo $exp->start_year ?? ""; ?> - <?php echo $exp->is_current ? "Sekarang" : ($exp->end_year ?? ""); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo $exp->company ?? ""; ?> <?php if(!empty($exp->location)): ?> | <?php echo $exp->location; ?> <?php endif; ?></div>
            <div class="item-desc"><?php echo nl2br(htmlspecialchars($exp->description ?? "")); ?></div>
        </div>
        <?php endforeach; ?>
        <?php elseif(count($experiences) > 1 && count($educations) == 0): ?>
        <div class="full-heading">Pengalaman Kerja Lainnya</div>
        <?php foreach(array_slice($experiences, 1) as $exp): ?>
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%; font-size: 10pt;"><?php echo $exp->position ?? ""; ?></td>
                    <td class="item-date" style="width: 25%;"><?php echo $exp->start_year ?? ""; ?> - <?php echo $exp->is_current ? "Sekarang" : ($exp->end_year ?? ""); ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo $exp->company ?? ""; ?> <?php if(!empty($exp->location)): ?> | <?php echo $exp->location; ?> <?php endif; ?></div>
            <div class="item-desc"><?php echo nl2br(htmlspecialchars($exp->description ?? "")); ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if(count($projects) > 0): ?>
        <div class="full-heading">Proyek & Portofolio</div>
        <?php foreach($projects as $proj): ?>
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%; font-size: 10pt;"><?php echo $proj->name ?? ""; ?></td>
                    <td class="item-date" style="width: 25%;"><?php echo $proj->year ?? $proj->link ?? ""; ?></td>
                </tr>
            </table>
            <?php
                $projSub = array_filter([$proj->role ?? "", $proj->technologies ?? "", (!empty($proj->year) ? $proj->link ?? "" : "")]);
            ?>
            <?php if(!empty($projSub)): ?>
            <div class="item-subtitle"><?php echo implode(" | ", $projSub); ?></div>
            <?php endif; ?>
            <div class="item-desc"><?php echo nl2br(htmlspecialchars($proj->description ?? "")); ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if(count($internships) > 0): ?>
        <div class="full-heading">Pengalaman Magang</div>
        <?php foreach($internships as $int): ?>
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%; font-size: 10pt;"><?php echo $int->position ?? ""; ?></td>
                    <td class="item-date" style="width: 25%;"><?php echo $int->start_year ?? ""; ?> - <?php echo $int->end_year ?? ""; ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo $int->company ?? ""; ?> <?php if(!empty($int->location)): ?> | <?php echo $int->location; ?> <?php endif; ?></div>
            <div class="item-desc"><?php echo nl2br(htmlspecialchars($int->description ?? "")); ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <?php if(count($organizations) > 0): ?>
        <div class="full-heading">Pengalaman Organisasi & Kepanitiaan</div>
        <?php foreach($organizations as $org): ?>
        <div class="item-block">
            <table class="item-header-table" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="item-title" style="width: 75%; font-size: 10pt;"><?php echo $org->role ?? ""; ?></td>
                    <td class="item-date" style="width: 25%;"><?php echo $org->start_year ?? ""; ?> - <?php echo $org->end_year ?? ""; ?></td>
                </tr>
            </table>
            <div class="item-subtitle"><?php echo $org->name ?? ""; ?></div>
            <div class="item-desc"><?php echo nl2br(htmlspecialchars($org->description ?? "")); ?></div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</body>
</html>