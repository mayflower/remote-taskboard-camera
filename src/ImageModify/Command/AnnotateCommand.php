<?php
namespace Rtc\ImageModify\Command;

use \Imagick;
use \ImagickDraw;
use \ImagickPixel;

/**
 * Annotates the image with text.
 *
 * @package Rtc\ImageModify\Command
 */
class AnnotateCommand extends CommandAbstract implements CommandInterface
{
    protected $_description = 'Annotate image with text.';

    protected $_baseDir      = '';
    protected $_imageFile    = '';

    protected $_fontFile     = '';
    protected $_fontColorHex = '#ec7404';

    /**
     * Initialize command.
     *
     * @param string $baseDir      Absolute path to base directory.
     * @param string $imageFile    Absolute path to image file.
     *
     * @throws \Exception on font file error.
     */
    public function __construct($baseDir, $imageFile)
    {
        $this->_baseDir   = $baseDir;
        $this->_imageFile = $imageFile;
        $this->_fontFile  = $this->_baseDir . DIRECTORY_SEPARATOR . "assets/fonts/arial-rounded.ttf";

        if (!file_exists($this->_fontFile) || !is_readable($this->_fontFile)) {
            throw new \Exception(sprintf("AnnotateCommand: Font file %s is not found/readable.", $this->_fontFile));
        }
    }

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $color = new ImagickPixel();
        $color->setColor($this->_fontColorHex);

        $drawText = new ImagickDraw();
        $drawText->setFillColor($color);
        $drawText->setFont($this->_fontFile);
        $drawText->setFontSize(100);

        $image->annotateImage($drawText, 20, 100, 0, date("D. d. M.  H:i", filemtime($this->_imageFile)));
    }
}