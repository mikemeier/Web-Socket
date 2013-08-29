<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputInterface;

class CwdCommand extends AbstractUserCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    protected function doExecute(InputInterface $input, Console $console)
    {
        $this->writeCwd($console);
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('pwd');
    }
}