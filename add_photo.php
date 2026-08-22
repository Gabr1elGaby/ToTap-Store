<?php
$file = 'app/Http/Controllers/CvController.php';
$content = file_get_contents($file);

$content = str_replace(
    "'photo' => '',",
    "'photo' => 'https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff&size=256',",
    $content
);

// Wait, the user explicitly asked for a PHOTO ("yang ada fotonya ya dikasih juga fotonya"),
// which implies they don't want the initials (which ui-avatars generates).
// They want a real human face.
// I will use a pravatar URL.

$content = str_replace(
    "'photo' => 'https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff&size=256',",
    "'photo' => 'https://i.pravatar.cc/300?img=11',",
    $content
);

// Also check if I had already replaced it
$content = str_replace(
    "'photo' => '',",
    "'photo' => 'https://i.pravatar.cc/300?img=11',",
    $content
);

file_put_contents($file, $content);
echo "Added photo to mock data.\n";
