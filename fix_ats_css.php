<?php
$file = 'resources/views/cv/templates/ats.blade.php';
$content = file_get_contents($file);

// Let's just manually replace the head styles to be safe
$regex = '/<style>.*?<\/style>/s';
$styles = <<<CSS
<style>
        @page { margin: 0px; }
        html { height: 100%; margin: 0; padding: 0; background-color: #fff; }
        body {
            padding: 40px 50px;
            word-wrap: break-word;
            word-break: break-word;
            overflow-wrap: break-word;
            font-family: 'Helvetica', 'Arial', sans-serif;
            margin: 0;
            font-size: 11pt;
            color: #000000;
            line-height: 1.4;
            box-sizing: border-box;
        }
        table { border-collapse: collapse; table-layout: fixed; width: 100%; }
        td { word-wrap: break-word; word-break: break-word; overflow-wrap: break-word; }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .name {
            font-size: 24pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0 0 5px 0;
            letter-spacing: 1px;
        }
        .contact-info {
            font-size: 10pt;
            color: #333333;
        }
        .section-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1.5px solid #000000;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 10px;
            letter-spacing: 1px;
        }
        .item-title-row {
            width: 100%;
            margin-bottom: 2px;
        }
        .item-title {
            font-weight: bold;
            font-size: 11pt;
        }
        .item-date {
            text-align: right;
            font-size: 10.5pt;
         white-space: nowrap; }
        .item-subtitle {
            font-style: italic;
            font-size: 10.5pt;
            margin-bottom: 4px;
        }
        .item-desc {
            font-size: 10.5pt;
            text-align: justify;
            margin-bottom: 12px;
        }
        .skills-list {
            margin: 0;
            padding-left: 20px;
            font-size: 10.5pt;
        }
        .skills-list li {
            margin-bottom: 3px;
        }
    </style>
CSS;

$content = preg_replace($regex, $styles, $content);

file_put_contents($file, $content);
echo "Manually fixed ATS styles.\n";
