<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Distorts the image.
 *
 * If the camera is not places right in front of the taskboard, the taskboard is
 * distorted on the image. Therefore there must be done a perspective correction.
 *
 * @package Rtc\ImageModify\Command
 */
class DistortCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Distorting image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $imageHeight = $image->getImageHeight();
        $imageWidth  = $image->getImageWidth();

        $controlPoints = array(
            # top left
            0,0, -1000,-250,
            # top right
            $imageWidth,0, $imageWidth,0,
            # bottom right
            $imageWidth,$imageHeight, $imageWidth,$imageHeight,
            # bottom left
            0,$imageHeight, -1000,$imageHeight+390,
        );

        $image->distortImage(\Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
        $image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);
    }
}