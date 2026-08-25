<?php
$uData = isset($userData) ? $userData : get_defined_vars();
$cvRaw = $uData["cv"] ?? ($cv ?? ($data ?? []));
$data = is_object($cvRaw) ? $cvRaw : (object)$cvRaw;

$getVal = function($item, ...$keys) {
    foreach ($keys as $k) {
        if (is_object($item) && isset($item->$k) && !empty($item->$k)) return $item->$k;
        if (is_array($item) && isset($item[$k]) && !empty($item[$k])) return $item[$k];
    }
    return "";
};

$getCol = function($key) use ($uData) {
    if (isset($uData[$key]) && is_array($uData[$key])) {
        return array_map(function($i) { return is_array($i) ? (object)$i : $i; }, $uData[$key]);
    }
    if (isset($uData[$key]) && $uData[$key] instanceof \Illuminate\Support\Collection) {
        return $uData[$key]->map(function($i) { return is_array($i) ? (object)$i : $i; })->all();
    }
    return [];
};

$educations    = $getCol("educations");
$experiences   = $getCol("experiences");
$internships   = $getCol("internships");
$organizations = $getCol("organizations");
$projects      = $getCol("projects");
$certificates  = $getCol("certificates");
$skills        = $getCol("skills");
$tools         = $getCol("tools");

$initials = "CV";
if (!empty($data->name)) {
    $words = explode(" ", trim($data->name));
    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ""));
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Eksekutif & Manajerial</title>
    <style>
        @page {
            margin: 0px;
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
            color: #333333;
            line-height: 1.45;
        }
        table.main-layout {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.main-layout, table.main-layout tr, table.main-layout td {
            page-break-inside: auto !important;
        }
        
        .header {
            background-color: #264653;
            color: #ffffff;
            padding: 32pt 28pt 20pt 28pt;
            border-bottom: 5pt solid #d4af37;
        }
        
        .photo-wrapper {
            width: 75pt;
            height: 75pt;
            border-radius: 50%;
            border: 3pt solid #d4af37;
            background-color: #ffffff;
            overflow: hidden;
            display: inline-block;
            text-align: center;
            line-height: 75pt;
            font-size: 22pt;
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
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 1px;
            line-height: 1.15;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 11pt;
            font-weight: bold;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8pt;
        }
        .summary {
            font-size: 9pt;
            color: #e0f2f1;
            text-align: justify;
            line-height: 1.4;
        }
        
        .sidebar-td {
            width: 32%;
            background-color: #2a9d8f;
            color: #ffffff;
            padding: 25pt 16pt 20pt 18pt;
            vertical-align: top;
        }
        .content-td {
            width: 68%;
            background-color: #ffffff;
            padding: 25pt 28pt 20pt 24pt;
            vertical-align: top;
        }
        
        .left-heading {
            font-size: 10pt;
            font-weight: bold;
            color: #ffffff;
            border-bottom: 2px solid #d4af37;
            padding-bottom: 3pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            text-transform: uppercase;
            display: block;
            width: 100%;
        }
        .left-heading:first-of-type {
            margin-top: 0;
        }
        
        .contact-item {
            margin-bottom: 8pt;
            font-size: 8.5pt;
            line-height: 1.35;
        }
        .contact-item strong {
            display: block;
            color: #f4a261;
            font-size: 7.5pt;
            text-transform: uppercase;
            margin-bottom: 1pt;
        }
        
        .skill-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .skill-list li {
            font-size: 8.5pt;
            margin-bottom: 5pt;
            color: #ffffff;
        }
        
        .right-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #264653;
            border-bottom: 2px solid #2a9d8f;
            padding-bottom: 3pt;
            margin-bottom: 10pt;
            margin-top: 16pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .right-heading:first-of-type {
            margin-top: 0;
        }
        
        .item-block {
            margin-bottom: 10pt;
        }
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #264653;
        }
        .item-meta {
            font-size: 8.5pt;
            color: #2a9d8f;
            font-weight: bold;
            margin-bottom: 3pt;
        }
        .item-desc {
            font-size: 8.5pt;
            color: #4a5568;
            text-align: justify;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <div class="header">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td valign="top" style="width: 85pt;">
                    <div class="photo-wrapper">
                        <?php if(!empty($data->photo)): ?>
                        <img src="<?php echo $data->photo; ?>">
                        <?php else: ?>
                        <?php echo $initials; ?>
                        <?php endif; ?>
                    </div>
                </td>
                <td valign="top" style="padding-left: 18pt;">
                    <div class="name"><?php echo !empty($data->name) ? $data->name : "NAMA LENGKAP"; ?></div>
                    <div class="job-title"><?php echo !empty($data->job_title) ? $data->job_title : "POSISI / PEKERJAAN"; ?></div>
                    <?php
                        $summaryText = $getVal($data, "profile", "summary", "about");
                    ?>
                    <?php if($summaryText !== ""): ?>
                    <div class="summary"><?php echo nl2br(htmlspecialchars($summaryText)); ?></div>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-layout" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sidebar-td">
                <div class="left-heading">Kontak</div>
                <?php if(!empty($data->phone)): ?>
                <div class="contact-item">
                    <strong>Telepon / WA</strong>
                    <?php echo $data->phone; ?>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->email)): ?>
                <div class="contact-item">
                    <strong>Email</strong>
                    <?php echo $data->email; ?>
                </div>
                <?php endif; ?>
                <?php if($getVal($data, "address", "location") !== ""): ?>
                <div class="contact-item">
                    <strong>Domisili</strong>
                    <?php echo $getVal($data, "address", "location"); ?>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->linkedin)): ?>
                <div class="contact-item">
                    <strong>LinkedIn</strong>
                    <?php echo $data->linkedin; ?>
                </div>
                <?php endif; ?>
                <?php if(!empty($data->website)): ?>
                <div class="contact-item">
                    <strong>Website</strong>
                    <?php echo $data->website; ?>
                </div>
                <?php endif; ?>

                <?php if(count($skills) > 0): ?>
                <div class="left-heading">Keahlian</div>
                <ul class="skill-list">
                    <?php foreach($skills as $skill): ?>
                    <li>• <?php echo $skill->name ?? ""; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if(count($tools) > 0): ?>
                <div class="left-heading">Tools & Software</div>
                <ul class="skill-list">
                    <?php foreach($tools as $tool): ?>
                    <li>• <?php echo $tool->name ?? ""; ?></li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>

                <?php if(count($certificates) > 0): ?>
                <div class="left-heading">Sertifikasi</div>
                <?php foreach($certificates as $cert): ?>
                <div style="margin-bottom: 8pt;">
                    <strong style="font-size: 8.5pt; color: #ffffff; display: block;"><?php echo $cert->name ?? ""; ?></strong>
                    <span style="color: #e2e8f0; font-size: 7.5pt;"><?php echo $cert->year ?? ""; ?></span>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </td>

            <td class="content-td">
                <?php if(count($experiences) > 0): ?>
                <div class="right-heading">Pengalaman Kerja</div>
                <?php foreach($experiences as $exp): ?>
                <div class="item-block">
                    <div class="item-title"><?php echo $exp->position ?? ""; ?></div>
                    <div class="item-meta"><?php echo $exp->company ?? ""; ?> | <?php echo $exp->start_year ?? ""; ?> - <?php echo $exp->is_current ? "Sekarang" : ($exp->end_year ?? ""); ?></div>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($exp->description ?? "")); ?></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
                
                <?php if(count($educations) > 0): ?>
                <div class="right-heading">Riwayat Pendidikan</div>
                <?php foreach($educations as $edu): ?>
                <div class="item-block">
                    <div class="item-title"><?php echo $edu->institution ?? ""; ?></div>
                    <?php
                        $deg = $edu->degree ?? "";
                        $maj = $getVal($edu, "major", "field");
                    ?>
                    <div class="item-meta"><?php echo $deg; ?><?php echo $maj !== "" ? ($deg !== "" ? " - " : "") . $maj : ""; ?> | <?php echo $edu->start_year ?? ""; ?> - <?php echo $edu->end_year ?? ""; ?></div>
                    <?php if(!empty($edu->description)): ?>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($edu->description)); ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if(count($internships) > 0): ?>
                <div class="right-heading">Pengalaman Magang</div>
                <?php foreach($internships as $int): ?>
                <div class="item-block">
                    <div class="item-title"><?php echo $int->position ?? ""; ?></div>
                    <div class="item-meta"><?php echo $int->company ?? ""; ?> | <?php echo $int->start_year ?? ""; ?> - <?php echo $int->end_year ?? ""; ?></div>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($int->description ?? "")); ?></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if(count($organizations) > 0): ?>
                <div class="right-heading">Pengalaman Organisasi</div>
                <?php foreach($organizations as $org): ?>
                <div class="item-block">
                    <div class="item-title"><?php echo $org->role ?? ""; ?></div>
                    <div class="item-meta"><?php echo $org->name ?? ""; ?> | <?php echo $org->start_year ?? ""; ?> - <?php echo $org->end_year ?? ""; ?></div>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($org->description ?? "")); ?></div>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>

                <?php if(count($projects) > 0): ?>
                <div class="right-heading">Proyek & Portofolio</div>
                <?php foreach($projects as $proj): ?>
                <div class="item-block">
                    <div class="item-title"><?php echo $proj->name ?? ""; ?></div>
                    <?php
                        $projSub = array_filter([$proj->role ?? "", $proj->technologies ?? "", (!empty($proj->year) ? $proj->link ?? "" : "")]);
                    ?>
                    <div class="item-meta"><?php echo implode(" | ", $projSub); ?></div>
                    <?php if(!empty($proj->description)): ?>
                    <div class="item-desc"><?php echo nl2br(htmlspecialchars($proj->description)); ?></div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <?php endif; ?>
            </td>
        </tr>
    </table>
</body>
</html>