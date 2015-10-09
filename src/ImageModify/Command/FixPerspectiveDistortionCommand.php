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

        $controlPoints = array(
            # top left
            138,1020, 138,1020,
            # top right
            4300,1027, 4300,1020,
            # bottom right
            4099,2897, 4300,3097,
            # bottom left
            334,2924, 138,3097
        );

        $image->distortImage(\Imagick::DISTORTION_PERSPECTIVE, $controlPoints, true);
        $image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);

        $image->cropImage(4200, 2300, 390, 800);
        $image->setImagePage(4200, 2300, 0, 0);
    }
}