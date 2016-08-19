<?php
namespace Rtc\ImageOrganize;

use \League\CLImate\CLImate;
use Rtc\ImageOrganize\Command;

/**
 * Invoker for image modification commands.
 *
 * @package Rtc\ImageOrganize
 */
class ImageOrganizeInvoker {
    protected $_batchJobs = array();

    public function addBatchJob($name, $commands) {
        $this->_batchJobs[$name] = $commands;
    }

    public function organize($jobName, CLImate $climate, $imageDirectory) {
        if (!isset($this->_batchJobs[$jobName])) {
            throw new \Exception("ImageOrganize batch job " . $jobName .  " does not exist!");
        }

        $climate->wisper('Image organize:')->br();

        $progress = $climate->progress()->total(count($this->_batchJobs[$jobName]));

        foreach ($this->_batchJobs[$jobName] as $count => $command) {
            $progress->advance(1, $command->getDescription());
            $command->execute($imageDirectory);
        }
    }
}