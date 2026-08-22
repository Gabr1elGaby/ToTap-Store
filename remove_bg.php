<?php
$inputFile = 'C:\Users\threv\.gemini\antigravity\brain\3309e6c8-318c-4b64-874f-069b6707005e\.user_uploaded\media_1787287991986.jpg';
$outputFile = 'd:\Bisnis\website\public\images\kategori-game-4.png';

$img = imagecreatefromjpeg($inputFile);

// Buat gambar true color dengan alpha channel
$width = imagesx($img);
$height = imagesy($img);
$newImg = imagecreatetruecolor($width, $height);
imagealphablending($newImg, false);
imagesavealpha($newImg, true);

// Loop setiap piksel, jika hampir hitam, ubah jadi transparan
for ($x = 0; $x < $width; $x++) {
    for ($y = 0; $y < $height; $y++) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Toleransi warna hitam (misal rgb < 25)
        if ($r < 25 && $g < 25 && $b < 25) {
            // Transparan
            $color = imagecolorallocatealpha($newImg, $r, $g, $b, 127);
            imagesetpixel($newImg, $x, $y, $color);
        } else {
            // Warna asli
            $color = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
            imagesetpixel($newImg, $x, $y, $color);
        }
    }
}

imagepng($newImg, $outputFile);
imagedestroy($img);
imagedestroy($newImg);

echo "Image background removed and saved as PNG.\n";
