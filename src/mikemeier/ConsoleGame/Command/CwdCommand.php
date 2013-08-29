<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

class CwdCommand extends AbstractUserCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $this->writeEmptyLine($console);
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('pwd');
    }
}