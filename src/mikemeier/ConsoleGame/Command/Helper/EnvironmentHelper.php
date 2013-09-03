<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Console\Environment;

class EnvironmentHelper extends AbstractHelper
{
    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    public function setCwd(Console $console, Directory $directory = null)
    {
        $this->getEnvironment($console)->setCwd($directory);
        return $this;
    }

    /**
     * @param Console $console
     * @return Directory
     */
    public function getCwd(Console $console)
    {
        return $this->getEnvironment($console)->getCwd();
    }

    /**
     * @param Console $console
     * @return Environment
     */
    public function getEnvironment(Console $console)
    {
        return $console->getClient()->getEnvironment();
    }
}