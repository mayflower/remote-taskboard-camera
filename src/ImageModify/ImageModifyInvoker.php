<?php
namespace Rtc\ImageModify;

use \Imagick;
use \League\CLImate\CLImate;
use Rtc\ImageModify\Command;

/**
 * Invoker for image modification commands.
 *
 * @package Rtc\ImageModify
 */
class ImageModifyInvoker {
    protected $_batchJobs = array();

    public function addBatchJob($name, $commands) {
        $this->_batchJobs[$name] = $commands;
    }

    public function modify($jobName, CLImate $climate, Imagick $image) {
        if (!isset($this->_batchJobs[$jobName])) {
            throw new \Exception("ImageModify batch job " . $jobName .  " does not exist!");
        }

        $climate->wisper('Image modifications:')->br();

        $progress = $climate->progress()->total(count($this->_batchJobs[$jobName]));

        foreach ($this->_batchJobs[$jobName] as $count => $command) {
            $progress->advance(1, $command->getDescription());
            $command->execute($image);
        }
    }
}