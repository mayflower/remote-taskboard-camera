<?php

namespace Rtc\ImageOrganize\Command;

use \Imagick;

/**
 * Abstract class for all ImageOrganize commands.
 *
 * @package Rtc\ImageOrganize\Command
 */
abstract class CommandAbstract implements CommandInterface {

    /**
     * @var string
     */
    protected $_description = '';

    /**
     * Returns a description text of the command.
     *
     * @return string Command description.
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Command execution.
     *
     * @param string $imageDirectory
     */
    public function execute($imageDirectory)
    {
    }
}