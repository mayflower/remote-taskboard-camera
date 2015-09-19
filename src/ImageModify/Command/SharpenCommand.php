<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Sharpens the image.
 *
 * @package Rtc\ImageModify\Command
 */
class SharpenCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Sharpening image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->sharpenImage(2,1);
    }
}