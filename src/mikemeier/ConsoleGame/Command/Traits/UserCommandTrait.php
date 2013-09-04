<?php

namespace mikemeier\ConsoleGame\Command\Traits;

use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Console\Console;

trait UserCommandTrait
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