<?php

namespace mikemeier\ConsoleGame\Output\Line\Decorator;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;

interface DecoratorInterface
{
    /**
     * @param Line $line
     * @param Console $console
     * @return DecoratorInterface
     */
    public function decorate(Line $line, Console $console);

    /**
     * @return int
     */
    public function getPriority();
}