<?php
namespace Rtc\ImageOrganize\Command;

/**
 * Identifies deprecated images and deletes it.
 *
 * @package Rtc\ImageOrganize\Command
 */
class DeleteOldImagesCommand extends CommandAbstract implements CommandInterface
{
    protected $_description = 'Identify and deletes deprecated images.';

    /**
     * Command execution.
     *
     * @param string $imageDirectory
     */
    public function execute($imageDirectory) {

        $cleanupDeadline = strtotime('last month');

        foreach (new \DirectoryIterator($imageDirectory) as $fileInfo) {
            if($fileInfo->isDot()) continue;

            $dateFromFilename = strtotime(substr($fileInfo->getFilename(), 0, 10));

            if ($dateFromFilename < $cleanupDeadline) {
                unlink($imageDirectory . DIRECTORY_SEPARATOR . $fileInfo->getFilename());
            }
        }
    }
}