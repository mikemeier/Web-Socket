<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class MkdirCommand extends AbstractUserCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $name = $input->getArgument('name');
        if(!$sanitized = preg_replace('/[^a-zA-Z]+/', '', $name)){
            $console->write('Invalid name: '. $name, 'error');
            return;
        }

        $cwd = $this->getCwd($console);
        if($cwd->getChild($sanitized)){
            $console->write('Already exists: '. $sanitized, 'error');
            return;
        }

        $this->getDirectoryRepository()->createDirectory($cwd, $sanitized);
        $console->write('OK', 'success');
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('name', InputArgument::REQUIRED, 'Name of the directory to create')
        ));
    }
}