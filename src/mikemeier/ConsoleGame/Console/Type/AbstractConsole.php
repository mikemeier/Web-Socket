<?php

namespace mikemeier\ConsoleGame\Console\Type;

use mikemeier\ConsoleGame\Console\Console;

abstract class AbstractConsole implements ConsoleInterface
{
    /**
     * @var ConsoleInterface
     */
    protected $clientConsole;

    /**
     * @param Console $console
     * @return $this
     */
    public function setClientConsole(Console $console)
    {
        $this->clientConsole = $console;
        return $this;
    }
}