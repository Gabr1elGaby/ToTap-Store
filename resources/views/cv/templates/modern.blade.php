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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV Modern Minimalis</title>
    <style>
        @page {
            margin: 18mm 20mm 18mm 20mm;
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
        
        .header-table {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 2pt solid #0284c7;
            padding-bottom: 12pt;
            margin-bottom: 14pt;
        }
        .header-avatar-td {
            width: 70pt;
            vertical-align: middle;
        }
        .header-info-td {
            vertical-align: middle;
            padding-left: 14pt;
        }
        .avatar-box {
            width: 65pt;
            height: 65pt;
            border-radius: 50%;
            background-color: #0f172a;
            color: #0284c7;
            font-size: 20pt;
            font-weight: bold;
            text-align: center;
            line-height: 65pt;
            border: 2.5pt solid #0284c7;
            overflow: hidden;
        }
        .avatar-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name {
            font-size: 22pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            line-height: 1.1;
            margin-bottom: 2pt;
        }
        .job-title {
            font-size: 11pt;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 6pt;
        }
        .contact-row {
            font-size: 8.5pt;
            color: #64748b;
            line-height: 1.4;
        }
        .contact-row span {
            color: #0f172a;
            font-weight: 500;
        }
        
        .section-heading {
            font-size: 11pt;
            font-weight: bold;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1.5pt solid #0284c7;
            padding-bottom: 3pt;
            margin-top: 14pt;
            margin-bottom: 8pt;
        }
        .section-heading:first-of-type {
            margin-top: 0;
        }
        
        .summary-text {
            font-size: 9pt;
            color: #475569;
            text-align: justify;
            margin-bottom: 10pt;
            line-height: 1.45;
        }
        
        .item-block {
            margin-bottom: 10pt;
            page-break-inside: avoid;
        }
        .item-header-table {
            width: 100%;
            margin-bottom: 2pt;
            border-collapse: collapse;
        }
        .item-title {
            font-size: 10pt;
            font-weight: bold;
            color: #0f172a;
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
            color: #0284c7;
            margin-bottom: 2pt;
        }
        .item-desc {
            font-size: 8.5pt;
            color: #475569;
            text-align: justify;
            line-height: 1.4;
        }
        
        .skills-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6pt;
        }
        .skills-table td {
            vertical-align: top;
            padding: 2pt 6pt 2pt 0;
            font-size: 8.5pt;
            color: #334155;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="header-avatar-td">
                <div class="avatar-box">
                    <?php if(!empty($data->photo)): ?>
                        <img src="<?php echo $data->photo; ?>">
                    <?php else: ?>
                        <?php echo $initials; ?>
                    <?php endif; ?>
                </div>
            </td>
            <td class="header-info-td">
                <div class="name"><?php echo !empty($data->name) ? $data->name : "NAMA LENGKAP"; ?></div>
                <div class="job-title"><?php echo !empty($data->job_title) ? $data->job_title : "POSISI / PEKERJAAN"; ?></div>
                <div class="contact-row">
                    <?php
                        $contacts = [];
                        if (!empty($data->phone)) $contacts[] = "Telepon: <span>" . e($data->phone) . "</span>";
                        if (!empty($data->email)) $contacts[] = "Email: <span>" . e($data->email) . "</span>";
                        $addr = $getVal($data, "address", "location");
                        if ($addr !== "") $contacts[] = "Domisili: <span>" . e($addr) . "</span>";
                        if (!empty($data->linkedin)) $contacts[] = "LinkedIn: <span>" . e($data->linkedin) . "</span>";
                        if (!empty($data->website)) $contacts[] = "Website: <span>" . e($data->website) . "</span>";
                        echo implode(" &nbsp;|&nbsp; ", $contacts);
                    ?>
                </div>
            </td>
        </tr>
    </table>

    <!-- RINGKASAN PROFIL -->
    <?php
        $summaryText = $getVal($data, "profile", "summary", "about");
    ?>
    <?php if($summaryText !== ""): ?>
    <div class="section-heading">Ringkasan Profil</div>
    <div class="summary-text">
        <?php echo nl2br(htmlspecialchars($summaryText)); ?>
    </div>
    <?php endif; ?>

    <!-- KEAHLIAN, TOOLS & SERTIFIKASI -->
    <?php if(count($skills) > 0 || count($tools) > 0 || count($certificates) > 0): ?>
    <div class="section-heading">Keahlian & Sertifikasi</div>
    <table class="skills-table">
        <tr>
            <?php if(count($skills) > 0 || count($tools) > 0): ?>
            <td style="width: 50%;">
                <?php if(count($skills) > 0): ?>
                <div><strong>Keahlian:</strong> <?php echo implode(", ", array_map(fn($s) => $s->name ?? "", $skills)); ?></div>
                <?php endif; ?>
                <?php if(count($tools) > 0): ?>
                <div style="margin-top: 3pt;"><strong>Tools / Software:</strong> <?php echo implode(", ", array_map(fn($t) => $t->name ?? "", $tools)); ?></div>
                <?php endif; ?>
            </td>
            <?php endif; ?>
            <?php if(count($certificates) > 0): ?>
            <td style="width: 50%;">
                <strong>Sertifikasi:</strong>
                <?php 
                    $certList = [];
                    foreach ($certificates as $c) {
                        $certList[] = ($c->name ?? "") . (!empty($c->year) ? " (" . $c->year . ")" : "");
                    }
                    echo implode(", ", $certList);
                ?>
            </td>
            <?php endif; ?>
        </tr>
    </table>
    <?php endif; ?>

    <!-- PENGALAMAN KERJA -->
    <?php if(count($experiences) > 0): ?>
    <div class="section-heading">Pengalaman Kerja</div>
    <?php foreach($experiences as $exp): ?>
    <div class="item-block">
        <table class="item-header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="item-title" style="width: 70%;"><?php echo $exp->position ?? ""; ?></td>
                <td class="item-date" style="width: 30%;"><?php echo $exp->start_year ?? ""; ?> - <?php echo $exp->is_current ? "Sekarang" : ($exp->end_year ?? ""); ?></td>
            </tr>
        </table>
        <div class="item-subtitle"><?php echo $exp->company ?? ""; ?> <?php if(!empty($exp->location)): ?> | <?php echo $exp->location; ?> <?php endif; ?></div>
        <div class="item-desc"><?php echo nl2br(htmlspecialchars($exp->description ?? "")); ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- RIWAYAT PENDIDIKAN -->
    <?php if(count($educations) > 0): ?>
    <div class="section-heading">Riwayat Pendidikan</div>
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
    <?php endif; ?>

    <!-- PROYEK & PORTOFOLIO -->
    <?php if(count($projects) > 0): ?>
    <div class="section-heading">Proyek & Portofolio</div>
    <?php foreach($projects as $proj): ?>
    <div class="item-block">
        <table class="item-header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="item-title" style="width: 70%;"><?php echo $proj->name ?? ""; ?></td>
                <td class="item-date" style="width: 30%;"><?php echo $proj->year ?? $proj->link ?? ""; ?></td>
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

    <!-- PENGALAMAN MAGANG -->
    <?php if(count($internships) > 0): ?>
    <div class="section-heading">Pengalaman Magang</div>
    <?php foreach($internships as $int): ?>
    <div class="item-block">
        <table class="item-header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="item-title" style="width: 70%;"><?php echo $int->position ?? ""; ?></td>
                <td class="item-date" style="width: 30%;"><?php echo $int->start_year ?? ""; ?> - <?php echo $int->end_year ?? ""; ?></td>
            </tr>
        </table>
        <div class="item-subtitle"><?php echo $int->company ?? ""; ?> <?php if(!empty($int->location)): ?> | <?php echo $int->location; ?> <?php endif; ?></div>
        <div class="item-desc"><?php echo nl2br(htmlspecialchars($int->description ?? "")); ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>

    <!-- PENGALAMAN ORGANISASI -->
    <?php if(count($organizations) > 0): ?>
    <div class="section-heading">Pengalaman Organisasi</div>
    <?php foreach($organizations as $org): ?>
    <div class="item-block">
        <table class="item-header-table" cellpadding="0" cellspacing="0">
            <tr>
                <td class="item-title" style="width: 70%;"><?php echo $org->role ?? ""; ?></td>
                <td class="item-date" style="width: 30%;"><?php echo $org->start_year ?? ""; ?> - <?php echo $org->end_year ?? ""; ?></td>
            </tr>
        </table>
        <div class="item-subtitle"><?php echo $org->name ?? ""; ?></div>
        <div class="item-desc"><?php echo nl2br(htmlspecialchars($org->description ?? "")); ?></div>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>