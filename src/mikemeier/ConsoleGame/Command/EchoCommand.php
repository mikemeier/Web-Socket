<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;

class EchoCommand extends AbstractCommand
{
    /**
     * @param string $input
     * @param Console $console
     * @return CommandInterface
     */
    public function executeRaw($input, Console $console)
    {
        $console->writeEmptyDecoratedLine();
        $console->write($input);
        return $this;
    }
}