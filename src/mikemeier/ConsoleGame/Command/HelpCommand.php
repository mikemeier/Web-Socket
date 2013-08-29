<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class HelpCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $commandName = $input->getArgument('command');
        if(!($command = $console->getCommand($commandName)) || !$command->isAvailable($console)){
            $console->writeCommandNotFound($commandName);
            return;
        }

        $console->describe($command);
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('command', InputArgument::REQUIRED)
        ));
    }
}