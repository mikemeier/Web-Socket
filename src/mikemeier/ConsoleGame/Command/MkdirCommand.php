<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\DirectoryRepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class MkdirCommand extends AbstractCommand
{
    use DirectoryRepositoryHelperTrait;
    use EnvironmentHelperTrait;
    use RepositoryHelperTrait;
    use UserCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        $name = $input->getArgument('name');
        if(!$sanitized = preg_replace('/[^a-zA-Z]+/', '', $name)){
            $console->write('Invalid name: '. $name, 'error');
            return $this;
        }

        $cwd = $this->getEnvironmentHelper()->getCwd($console);
        if($cwd->getChild($sanitized)){
            $console->write('Already exists: '. $sanitized, 'error');
            return $this;
        }

        $this->getDirectoryRepository()->createDirectory($cwd, $sanitized);
        $console->write('OK', 'success');

        return $this;
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