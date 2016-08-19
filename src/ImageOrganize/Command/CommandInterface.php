<?php
namespace Rtc\ImageOrganize\Command;

/**
 * Interface CommandInterface
 *
 * @package Rtc\ImageOrganize\Command
 */
interface CommandInterface {

    /**
     * @param string $imageDirectory
     */
    public function execute($imageDirectory);

    /**
     * @return string
     */
    public function getDescription();
}