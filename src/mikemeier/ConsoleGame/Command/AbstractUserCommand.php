<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Console\Console;

abstract class AbstractUserCommand extends AbstractCommand
{
    use UserHelperTrait;

    /**
     * @param Console $console
     * @return bool
     */
    public function isAvailable(Console $console)
    {
        return $this->getUserHelper()->hasUser($console);
    }
}