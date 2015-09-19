<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Interface CommandInterface
 *
 * @package Rtc\ImageModify\Command
 */
interface CommandInterface {
    /**
     * @param Imagick $image
     */
    public function execute(Imagick $image);

    /**
     * @return string
     */
    public function getDescription();
}