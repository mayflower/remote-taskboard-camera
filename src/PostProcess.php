<?php
namespace Rtc;

use \League\CLImate\CLImate;

/**
 * Post process photo
 *
 * Improves the photo quality and adds a timestamp to the photo.
 */
class PostProcess
{
    protected $_climate;

    public function __construct()
    {
        $this->_climate = new CLImate();
        $this->_configure();
    }

    protected function _configure()
    {
        $this->_climate->description('Remote Taskboard Camera - Post processing');

        $this->_climate->arguments->add([
            'imagefile' => [
                'prefix'      => 'i',
                'longPrefix'  => 'imagefile',
                'description' => 'Image file (Default: Will be overwritten as outfile)',
                'required'    => true,
            ],
            'outfile' => [
                'prefix'      => 'o',
                'longPrefix'  => 'outfile',
                'description' => 'Alternative output file',
                'required'    => false,
            ],
            'basedir' => [
                'prefix'      => 'b',
                'longPrefix'  => 'basedir',
                'description' => 'Base directory',
                'required'    => false,
            ],
        ]);
    }

    public function execute()
    {
        try {
            $this->_climate->arguments->parse();

        } catch(\Exception $exception) {
            $this->_climate->usage();
            exit(1);
        }

        $imageFile = $this->_climate->arguments->get('imagefile');

        $outFile = $imageFile;
        if ($this->_climate->arguments->defined('outfile')) {
            $outFile = $this->_climate->arguments->get('outfile');
        }

        $baseDir = '.';
        if ($this->_climate->arguments->defined('basedir')) {
            $baseDir = $this->_climate->arguments->get('basedir');
        }

        $image = new \Imagick($imageFile);

        $imageHeight = $image->getImageHeight();
        $imageWidth  = $image->getImageWidth();

        $this->_climate->bold('Starting post processing...')->br();

        $progress = $this->_climate->progress()->total(100);
        $progress->advance(1, 'Correcting perspective.');
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

        $progress->advance(50, 'Cropping.');
        $image->cropImage($image->getImageWidth(), $image->getImageHeight()-500, 0, 200);
        $image->setImagePage($image->getImageWidth(), $image->getImageHeight(), 0, 0);

        $progress->advance(10, 'Resizing.');
        $image->resizeImage(
            $image->getImageWidth() * 0.75,
            $image->getImageHeight() * 0.75,
            \Imagick::FILTER_SINC,
            1
        );
        $progress->advance(10, 'Adding contrast.');
        $image->contrastImage(20);
        $progress->advance(10, 'Correcting perspective.');
        $image->sharpenImage(2,1);

        // Add text to image
        $progress->advance(10, 'Annotate with text.');
        $drawText = new \ImagickDraw();
        $drawText->setFillColor('#ec7404');
        $drawText->setFont($baseDir . DIRECTORY_SEPARATOR . "assets/fonts/arial-rounded.ttf");
        $drawText->setFontSize(100);
        $image->annotateImage($drawText, 20, 100, 0, date("D. d. M.  H:i", filemtime($imageFile)));

        $progress->advance(5, 'Saving image to file.');
        $image->setImageCompression(\Imagick::COMPRESSION_JPEG);
        $image->setImageCompressionQuality(90);

        $image->writeImage($outFile);
        $progress->advance(4, 'Finished post processing.');
    }
}