<?php
$sourcePath = 'public/images/totap-logo.png';
$destPath = 'public/images/totap-logo-circle.png';

// Load original image
$sourceImg = imagecreatefrompng($sourcePath);
$width = imagesx($sourceImg);
$height = imagesy($sourceImg);

// We want a perfect square based on the smaller dimension
$size = min($width, $height);
$newImg = imagecreatetruecolor($size, $size);

// Preserve transparency
imagealphablending($newImg, false);
imagesavealpha($newImg, true);
$transparent = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
imagefill($newImg, 0, 0, $transparent);

// Create a circular mask
for ($x = 0; $x < $size; $x++) {
    for ($y = 0; $y < $size; $y++) {
        // Calculate distance from center
        $centerX = $size / 2;
        $centerY = $size / 2;
        $distance = sqrt(pow($x - $centerX, 2) + pow($y - $centerY, 2));

        if ($distance <= $size / 2) {
            // It's inside the circle, copy the pixel from the original image
            // We need to calculate where to pull it from (center-crop the original)
            $srcX = $x + ($width - $size) / 2;
            $srcY = $y + ($height - $size) / 2;
            
            $color = imagecolorat($sourceImg, $srcX, $srcY);
            imagesetpixel($newImg, $x, $y, $color);
        }
    }
}

// Save the new circular image
imagepng($newImg, $destPath);
imagedestroy($sourceImg);
imagedestroy($newImg);

// Now update the layout files to use the new circle icon for favicons
$files = [
    'resources/views/layouts/app.blade.php',
    'resources/views/layouts/guest.blade.php',
    'resources/views/welcome.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace(
            "href=\"{{ asset('images/totap-logo.png')",
            "href=\"{{ asset('images/totap-logo-circle.png')",
            $content
        );
        file_put_contents($file, $content);
    }
}

echo "Circular favicon generated and updated.\n";
