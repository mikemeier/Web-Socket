<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;

interface AutocompletableCommandInterface
{
    /**
     * @param string $input
     * @param Console $console
     * @return string|null
     */
    public function autocomplete($input, Console $console);
}