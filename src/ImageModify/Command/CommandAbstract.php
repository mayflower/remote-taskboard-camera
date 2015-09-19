<?php

namespace Rtc\ImageModify\Command;

use \Imagick;

/**
 * Abstract class for all ImageModify commands.
 *
 * @package Rtc\ImageModify\Command
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
     * @param Imagick $image
     */
    public function execute(Imagick $image)
    {
    }
}