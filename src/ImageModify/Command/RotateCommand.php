<?php
namespace Rtc\ImageModify\Command;

use \Imagick;
use \ImagickPixel;

/**
 * Rotates the image.
 *
 * @package Rtc\ImageModify\Command
 */
class RotateCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Rotating image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->rotateImage(new ImagickPixel(), 180);
    }
}