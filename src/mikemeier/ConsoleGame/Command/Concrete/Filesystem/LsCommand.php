<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Filesystem;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputInterface;

class LsCommand extends AbstractCommand
{
    use EnvironmentHelperTrait;
    use UserCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        foreach(array('.', '..') as $name){
            $this->outputDirectory($console, $name);
        }

        foreach($this->getEnvironmentHelper()->getCwd($console)->getChildren() as $child){
            $this->outputDirectory($console, $child->getName());
        }

        return $this;
    }

    /**
     * @param Console $console
     * @param string $name
     */
    protected function outputDirectory(Console $console, $name)
    {
        $line = new Line();
        $line->add(' d ');
        $line->add($name, 'directory');
        $console->writeLine($line);
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('dir');
    }
}