<?php
/**
 * Post process photo
 *
 * Improves the photo quality and adds a timestamp to the photo.
 */

if (!isset($argv[1]) || $argv[1] == "") {
    echo "Script called without filename parameter. Usage: php process-photo.php <filename> <basedir>\nAborting...\n";
    exit(1);
}
$imageFile = $argv[1];

if (!isset($argv[2]) || $argv[2] == "") {
    echo "Script called without basedir parameter. Usage: php process-photo.php <filename>  <basedir>\nAborting...\n";
    exit(1);
}
$baseDir = $argv[2];

// Post-processing
$image = new Imagick($imageFile);
$image->sharpenImage(2,1);
$image->contrastImage(20);
$image->scaleimage(
    $image->getImageWidth() * 0.75,
    $image->getImageHeight() * 0.75
);

// Add text to image
$drawText = new ImagickDraw();
$drawText->setFillColor('#ec7404');
$drawText->setFont($baseDir . "/fonts/arial-rounded.ttf");
$drawText->setFontSize(100);
$image->annotateImage($drawText, 20, 100, 0, date("D. d. M.  H:i", filemtime($imageFile)));

$image->setImageCompression(Imagick::COMPRESSION_JPEG);
$image->setImageCompressionQuality(95);
$image->writeImage($imageFile);
