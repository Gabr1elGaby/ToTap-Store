<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    "'elegant': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'bahasa'],",
    "'elegant': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'bahasa'],\n                          'modern': ['pribadi', 'profil', 'pendidikan', 'pengalaman', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'bahasa'],",
    $content
);

file_put_contents($file, $content);
echo "Added modern to templateConfig.\n";
