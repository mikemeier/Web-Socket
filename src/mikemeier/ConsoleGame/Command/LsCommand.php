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
    protected function doExecute(InputInterface $input, Console $console)
    {
        foreach(array('.', '..') as $default){
            $line = new Line();
            $line->add('d', 'tab-20');
            $line->add($default);
            $console->writeLine($line);
        }

        foreach($this->getCwd($console)->getChildren() as $child){
            $line = new Line();
            $line->add('d', 'tab-20');
            $line->add($child->getName());
            $console->writeLine($line);
        }
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array('dir');
    }
}