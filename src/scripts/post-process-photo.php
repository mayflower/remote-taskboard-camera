<?php
/**
 * Post process photo
 *
 * Improves the photo quality and adds a timestamp to the photo.
 */

if (!isset($argv[1]) || $argv[1] == "") {
    echo "Script called without filename parameter. Usage: php post-process-photo.php <filename> <basedir>\nAborting...\n";
    exit(1);
}
$imageFile = $argv[1];

if (!isset($argv[2]) || $argv[2] == "") {
    echo "Script called without basedir parameter. Usage: php post-process-photo.php <filename>  <basedir>\nAborting...\n";
    exit(1);
}
$baseDir = $argv[2];

// Post-processing
$image = new Imagick($imageFile);

$imageHeight = $image->getImageHeight();
$imageWidth  = $image->getImageWidth();

echo "-> Correcting perspective\n";
$controlPoints = array(
    # top left
    0,0, -1000,-250,
    # top right
    $imageWidth,0, $imageWidth,0,
    # bottom right
    $imageWidth,$imageHeight, $imageWidth,$imageHeight,
    # bottom left
    0,$imageHeight, -1000,$imageHeight+390,
);

$image->distortImage(Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
$image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);

echo "-> Cropping\n";
$image->cropImage($image->getImageWidth(), $image->getImageHeight()-500, 0, 200);
$image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);

echo "-> Resizing\n";
$image->resizeImage(
    $image->getImageWidth() * 0.75,
    $image->getImageHeight() * 0.75,
    Imagick::FILTER_SINC,
    1
);
echo "-> Adding contrast\n";
$image->contrastImage(20);
echo "-> Sharpening\n";
$image->sharpenImage(2,1);

// Add text to image
echo "-> Annotate with text\n";
$drawText = new ImagickDraw();
$drawText->setFillColor('#ec7404');
$drawText->setFont($baseDir . DIRECTORY_SEPARATOR . "assets/fonts/arial-rounded.ttf");
$drawText->setFontSize(100);
$image->annotateImage($drawText, 20, 100, 0, date("D. d. M.  H:i", filemtime($imageFile)));

echo "-> Saving to file\n";
$image->setImageCompression(Imagick::COMPRESSION_JPEG);
$image->setImageCompressionQuality(90);
$image->writeImage($imageFile);