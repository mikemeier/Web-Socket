<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Misc;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Server\Message\Message;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

class ClearCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        $console->getClient()->send(new Message('clear'));
        $console->writeEmptyDecoratedLine();
        return $this;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('cls');
    }
}