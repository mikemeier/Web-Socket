<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $console->writeEmptyDecoratedLine();

        /** @var CommandInterface $command */
        foreach(array_unique($console->getCommands()) as $command){
            if(!$command->isAvailable($console)){
                continue;
            }
            $console->write(' * '. $command->getName(), 'list');
        }
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'List all available commands';
    }
}