<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Filesystem;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

class CwdCommand extends AbstractCommand
{
    use UserCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        $console->writeEmptyDecoratedLine();
        return $this;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('pwd');
    }
}