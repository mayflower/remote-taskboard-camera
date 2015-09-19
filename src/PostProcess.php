<?php
namespace Rtc;

use Rtc\ImageModify\ImageModifyInvoker;
use Rtc\ImageModify\Command\FixPerspectiveDistortionCommand;
use Rtc\ImageModify\Command\ResizeCommand;
use Rtc\ImageModify\Command\ContrastCommand;
use Rtc\ImageModify\Command\SharpenCommand;
use Rtc\ImageModify\Command\AnnotateWithTextCommand;
use Rtc\ImageModify\Command\WriteToFileCommand;

use \Imagick;
use \League\CLImate\CLImate;

/**
 * Class PostProcess
 *
 * Handles the post processing after the image was shot and downloaded from the camera.
 *
 * @package Rtc
 */
class PostProcess {

    /**
     * @var bool
     */
    public $isInitialized = false;

    /**
     * @var CLImate
     */
    protected $_climate   = null;

    /**
     * @var string
     */
    protected $_imageFile = '';

    /**
     * @var string
     */
    protected $_outFile   = '';

    /**
     * @var string
     */
    protected $_baseDir   = '';

    /**
     * Defines the cli arguments.
     *
     * @throws \Exception
     */
    protected function _defineArguments()
    {
        $this->_climate->arguments->add([
            'imagefile'       => [
                'prefix'      => 'i',
                'longPrefix'  => 'imagefile',
                'description' => 'Image file (Default: Will be overwritten as outfile)',
                'required'    => true,
            ],
            'outfile'         => [
                'prefix'      => 'o',
                'longPrefix'  => 'outfile',
                'description' => 'Alternative output file',
                'required'    => false,
            ],
            'basedir'         => [
                'prefix'      => 'b',
                'longPrefix'  => 'basedir',
                'description' => 'Base directory',
                'required'    => false,
            ],
        ]);
    }

    /**
     * Validates the given cli arguments and initializes internal variables.
     *
     * @throws \Exception
     */
    protected function _validateArgumentValues()
    {
        $this->_imageFile = $this->_climate->arguments->get('imagefile');
        if (!file_exists($this->_imageFile)) {
            throw new \Exception(sprintf("Input image file %s does not exist!", $this->_imageFile));
        }
        if (!is_readable($this->_imageFile)) {
            throw new \Exception(sprintf("Input image file file %s is not readable!", $this->_imageFile));
        }

        $this->_outFile = $this->_imageFile;
        if ($this->_climate->arguments->defined('outfile')) {
            $this->_outFile = $this->_climate->arguments->get('outfile');
        }
        if (!touch($this->_outFile) || !is_writable($this->_outFile)) {
            throw new \Exception(sprintf("Output image file %s is not writable!", $this->_outFile));
        }

        $this->_baseDir = '.';
        if ($this->_climate->arguments->defined('basedir')) {
            $this->_baseDir = $this->_climate->arguments->get('basedir');
        }
        if (!is_dir($this->_baseDir)) {
            throw new \Exception(sprintf("Base directory is not a directory!", $this->_baseDir));
        }
    }

    /**
     * Modifies the given image by using an invoker with predefined commands.
     *
     * @param Imagick $image
     *
     * @throws \Exception
     */
    protected function _modifyImage(Imagick $image)
    {
        $imageModifyInvoker = new ImageModifyInvoker();
        $imageModifyInvoker->addBatchJob('team-taskboard',
            array(
                new FixPerspectiveDistortionCommand(),
                new ResizeCommand(),
                new ContrastCommand(),
                new SharpenCommand(),
                new AnnotateWithTextCommand($this->_baseDir, $this->_imageFile),
                new WriteToFileCommand($this->_outFile),
            )
        );

        $imageModifyInvoker->modify('team-taskboard', $this->_climate, $image);
    }

    /**
     * Initializes the post processing.
     *
     * @param CLImate $climate
     *
     * @throws \Exception
     */
    public function initialize(CLImate $climate)
    {
        if(!extension_loaded('imagick')) {
            throw new \Exception("Imagemagick extension not installed.");
        }

        $this->_climate = $climate;

        $this->_defineArguments();

        try {
            $this->_climate->arguments->parse();

        } catch(\Exception $exception) {
            $this->_climate->usage();
            exit(1);
        }

        $this->_validateArgumentValues();

        $this->isInitialized = true;
    }

    /**
     * Starts the post processing.
     *
     * @throws \Exception
     */
    public function start()
    {
        if (!$this->isInitialized) {
            throw new \Exception("PostProcess start without initialization!");
        }

        $image = new Imagick($this->_imageFile);

        $this->_climate->info('Starting post processing...');

        $this->_modifyImage($image);

        $this->_climate->info('Finished post processing!');
    }
}