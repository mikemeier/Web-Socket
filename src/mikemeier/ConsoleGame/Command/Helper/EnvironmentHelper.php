<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;

class EnvironmentHelper extends AbstractHelper
{
    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    public function setCwd(Console $console, Directory $directory = null)
    {
        $console->getClient()->getEnvironment()->setCwd($directory);
        return $this;
    }

    /**
     * @param Console $console
     * @return Directory
     */
    public function getCwd(Console $console)
    {
        return $console->getClient()->getEnvironment()->getCwd();
    }
}