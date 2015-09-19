<?php
namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Writes the image to a file and sets the image-type and -quality.
 *
 * @package Rtc\ImageModify\Command
 */
class WriteToFileCommand extends CommandAbstract implements CommandInterface {

    protected $_description = 'Writing image to outfile.';

    protected $_outFile = '';

    /**
     * Initialize command.
     * 
     * @param string $outFile  Absolute path to outfile where the image should be written to.
     */
    public function __construct($outFile) {
        $this->_outFile = $outFile;
    }

    /**
     * Command execution.
     *
     * @param Imagick $image
     */
    public function execute(Imagick $image) {
        $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(90);

        $image->writeImage($this->_outFile);
    }
}