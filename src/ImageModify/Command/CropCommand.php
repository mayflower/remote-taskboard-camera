<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Crops the image.
 *
 * @package Rtc\ImageModify\Command
 */
class CropCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Cropping image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->cropImage($image->getImageWidth(), $image->getImageHeight()-500, 0, 200);
        $image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);
    }
}