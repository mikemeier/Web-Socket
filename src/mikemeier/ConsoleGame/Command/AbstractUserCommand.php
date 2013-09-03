<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;

abstract class AbstractUserCommand extends AbstractCommand
{
    /**
     * @param Console $console
     * @return bool
     */
    public function isAvailable(Console $console)
    {
        return $this->getHelper('user')->hasUser($console);
    }
}