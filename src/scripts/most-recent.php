<?php
$photosDir = '/srv/gallery/Fotos/team-bahag-taskboard';

$photosArray = array();
foreach (new DirectoryIterator($photosDir) as $fileInfo) {
    if($fileInfo->isDot()) continue;
    $photosArray[] = $fileInfo->getFilename();
}

rsort($photosArray);

$mostRecentFile = $photosArray[0];

header('Content-Type: image/jpeg');
header('X-Sendfile: ' . $mostRecentFile);
header('Cache-Control: no-store, no-cache');
readfile($photosDir . DIRECTORY_SEPARATOR . $mostRecentFile);
