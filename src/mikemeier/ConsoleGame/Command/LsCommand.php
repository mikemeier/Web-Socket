<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputInterface;

class LsCommand extends AbstractUserCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        foreach(array('.', '..') as $name){
            $this->outputDirectory($console, $name);
        }

        foreach($this->getCwd($console)->getChildren() as $child){
            $this->outputDirectory($console, $child->getName());
        }
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