<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$content = str_replace("'languages' => 'nullable|array',", "", $content);

$lang_insert = <<<PHP
            if (!empty(\$data['languages'])) {
                foreach (\$data['languages'] as \$lang) {
                    DB::table('cv_languages')->insert([
                        'cv_id' => \$cvId,
                        'language' => \$lang['name'] ?? '',
                        'proficiency' => null,
                        'created_at' => now(), 'updated_at' => now()
                    ]);
                }
            }
PHP;
$content = str_replace($lang_insert, "", $content);

$content = str_replace("\$languages = DB::table('cv_languages')->where('cv_id', \$cvId)->get();", "", $content);
$content = str_replace("'languages' => \$languages,", "", $content);

file_put_contents($file, $content);
echo "Done removing languages from CvController.php\n";
