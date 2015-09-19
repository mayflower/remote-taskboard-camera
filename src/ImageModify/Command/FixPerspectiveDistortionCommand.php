<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Fixes the perspective distortion (long-lens distortion and viewing angle distortion) of the image.
 *
 * @package Rtc\ImageModify\Command
 */
class FixPerspectiveDistortionCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Fixing perspective distortion of the image.';

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

        $image->cropImage($image->getImageWidth(), $image->getImageHeight()-500, 0, 200);
        $image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);
    }
}