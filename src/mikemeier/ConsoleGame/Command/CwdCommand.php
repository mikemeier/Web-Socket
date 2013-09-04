<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

class CwdCommand extends AbstractCommand
{
    use UserCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $console->writeEmptyDecoratedLine();
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('pwd');
    }
}