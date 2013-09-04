<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class EchoCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface|void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $console->write(implode(" ", $input->getArgument('text')));
        return $this;
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(new InputArgument('text', InputArgument::IS_ARRAY)));
    }
}