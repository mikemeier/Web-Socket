<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;

interface InteractiveCommandInterface extends CommandInterface
{
    /**
     * @param Console $console
     * @return InteractiveCommandInterface
     */
    public function stop(Console $console);

    /**
     * @param Console $console
     * @param string $input
     * @return InteractiveCommandInterface
     */
    public function onInput(Console $console, $input);

    /**
     * @param Console $console
     * @param string $input
     * @return InteractiveCommandInterface
     */
    public function onCancel(Console $console, $input);

    /**
     * @param Console $console
     * @param string $input
     * @return InteractiveCommandInterface
     */
    public function onTab(Console $console, $input);
}