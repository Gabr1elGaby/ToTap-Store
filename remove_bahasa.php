<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

// Remove the HTML step for Bahasa
$content = preg_replace('/<!-- STEP: Bahasa -->.*?<\/div>\s*<\/div>/s', '', $content);

// Remove language from Javascript data
$content = str_replace("languages: [],", "", $content);

// Remove language from JSON stringify
$content = str_replace("languages: this.data.languages,", "", $content);

// Remove addLanguage method
$content = preg_replace('/addLanguage\(\) \{.*?\},\s*/s', '', $content);

// Remove 'bahasa' from steps array
$content = str_replace("{ id: 'bahasa', title: 'Bahasa' }", "", $content);

// Clean up trailing commas in steps array if necessary
$content = preg_replace('/,\s*]/', "\n                    ]", $content);

file_put_contents($file, $content);
echo "Done removing bahasa from create.blade.php\n";
