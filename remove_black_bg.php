<?php
// Function to remove black background and make it transparent
function removeBlackBackground($sourceFile, $outputFile, $tolerance = 30) {
    // Load the image
    $img = imagecreatefromjpeg($sourceFile);
    if (!$img) {
        die("Could not load image.");
    }

    // Get dimensions
    $width = imagesx($img);
    $height = imagesy($img);

    // Create a new true color image with alpha channel
    $newImg = imagecreatetruecolor($width, $height);
    imagesavealpha($newImg, true);
    $transColor = imagecolorallocatealpha($newImg, 0, 0, 0, 127);
    imagefill($newImg, 0, 0, $transColor);

    // Iterate over each pixel
    for ($x = 0; $x < $width; $x++) {
        for ($y = 0; $y < $height; $y++) {
            $rgb = imagecolorat($img, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // If the pixel is dark (close to black), make it transparent
            if ($r < $tolerance && $g < $tolerance && $b < $tolerance) {
                // Determine transparency based on how close to pure black it is to avoid hard edges
                // 0 is pure black (fully transparent), $tolerance is fully opaque
                $brightness = max($r, $g, $b);
                if ($brightness == 0) {
                    $alpha = 127; // fully transparent
                } else {
                    // Smooth transition
                    $alpha = 127 - (int)(127 * ($brightness / $tolerance));
                }
                
                // If it's very close to black, just make it completely transparent
                if ($brightness < 15) {
                    $alpha = 127;
                }
                
                $color = imagecolorallocatealpha($newImg, $r, $g, $b, $alpha);
                imagesetpixel($newImg, $x, $y, $color);
            } else {
                // Keep original color
                $color = imagecolorallocatealpha($newImg, $r, $g, $b, 0);
                imagesetpixel($newImg, $x, $y, $color);
            }
        }
    }

    // Save as PNG
    imagepng($newImg, $outputFile);
    imagedestroy($img);
    imagedestroy($newImg);
    echo "Successfully removed black background and saved to $outputFile\n";
}

$source = 'public/images/software-logo.jpg';
$dest = 'public/images/software-logo.png';
removeBlackBackground($source, $dest, 45); // Tolerance 45 for better edge blending
