<?php
namespace Rtc;

use \League\CLImate\CLImate;
use Rtc\ImageOrganize\Command\DeleteOldImagesCommand;
use Rtc\ImageOrganize\ImageOrganizeInvoker;

/**
 * Class ManageImages
 *
 * Several image management actions.
 *
 * @package Rtc
 */
class ManageImages {

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
    protected $_imageDirectory = '';

    /**
     * Defines the cli arguments.
     *
     * @throws \Exception
     */
    protected function _defineArguments()
    {
        $this->_climate->arguments->add([
            'imagedir'         => [
                'prefix'      => 'i',
                'longPrefix'  => 'imagedir',
                'description' => 'Image directory',
                'required'    => true,
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
        $this->_imageDirectory = $this->_climate->arguments->get('imagedir');
        if (!file_exists($this->_imageDirectory)) {
            throw new \Exception(sprintf("Image directory %s does not exist!", $this->_imageDirectory));
        }
        if (!is_readable($this->_imageDirectory)) {
            throw new \Exception(sprintf("Image directory %s is not readable!", $this->_imageDirectory));
        }
    }

    protected function _organizeImages()
    {
        $imageOrganizeInvoker = new ImageOrganizeInvoker();
        $imageOrganizeInvoker->addBatchJob('team-taskboard',
            array(
                new DeleteOldImagesCommand(),
            )
        );

        $imageOrganizeInvoker->organize('team-taskboard', $this->_climate, $this->_imageDirectory);
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
     * Starts the image organization.
     *
     * @throws \Exception
     */
    public function start()
    {
        if (!$this->isInitialized) {
            throw new \Exception("ManageImages start without initialization!");
        }

        $this->_climate->info('Starting image organization...');

        $this->_organizeImages();

        $this->_climate->info('Finished image organization!');
    }
}