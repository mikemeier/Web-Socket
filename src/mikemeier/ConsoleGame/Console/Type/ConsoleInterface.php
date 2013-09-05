<?php

namespace mikemeier\ConsoleGame\Console\Type;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

interface ConsoleInterface
{
    /**
     * @param Console $console
     * @return ConsoleInterface
     */
    public function setClientConsole(Console $console);

    /**
     * @param InputInterface $input
     * @return ConsoleInterface
     */
    public function process(InputInterface $input);
}