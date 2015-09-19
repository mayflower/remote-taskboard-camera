<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Changes the contrast of the image.
 *
 * @package Rtc\ImageModify\Command
 */
class ContrastCommand extends CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = 'Adding contrast to image.';

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->contrastImage(20);
    }
}