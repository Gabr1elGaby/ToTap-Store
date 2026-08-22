<?php
$file = 'resources/views/cv/create.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    "'fresh-graduate': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'prestasi'],",
    "'fresh-graduate': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'prestasi'],\n                    'elegant': ['pribadi', 'profil', 'pendidikan', 'magang', 'organisasi', 'project', 'skill', 'sertifikat', 'bahasa'],",
    $content
);

file_put_contents($file, $content);
echo "Added elegant to templateConfig.\n";
