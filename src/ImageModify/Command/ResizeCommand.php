<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Resizes the image.
 *
 * @package Rtc\ImageModify\Command
 */
class ResizeCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Resizing image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->resizeImage(
            $image->getImageWidth() * 0.75,
            $image->getImageHeight() * 0.75,
            \Imagick::FILTER_SINC,
            1
        );
    }
}