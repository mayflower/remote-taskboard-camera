<?php
require_once('vendor/autoload.php');

use Rtc\ManageImages;
use League\CLImate\CLImate;

try {
    $climate = new CLImate();
    $postProcess = new ManageImages();

    $description = 'Remote Taskboard Camera - Manage images';

    $climate->description($description);
    $climate->bold()->yellow(strtoupper($description));

    $postProcess->initialize($climate);
    $postProcess->start();

} catch (Exception $exception) {
    $climate->error('Error: ' . $exception->getMessage());
    exit(1);
}

exit(0);