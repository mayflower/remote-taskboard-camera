<?php
require_once('vendor/autoload.php');

use Rtc\PostProcess;
use League\CLImate\CLImate;

try {
    $climate = new CLImate();
    $postProcess = new PostProcess();

    $description = 'Remote Taskboard Camera - Post processing';

    $climate->description($description);
    $climate->bold()->yellow(strtoupper($description));

    $postProcess->initialize($climate);
    $postProcess->start();

} catch (Exception $exception) {
    $climate->error('Error: ' . $exception->getMessage());
    exit(1);
}

exit(0);