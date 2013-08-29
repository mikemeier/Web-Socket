<?php

namespace mikemeier\ConsoleGame\Output\Line\Decorator;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;

class PathDecorator extends AbstractDecorator
{
    /**
     * @param Line $line
     * @param Console $console
     * @return DecoratorInterface|void
     */
    public function decorate(Line $line, Console $console)
    {
        $environment = $console->getClient()->getEnvironment();
        if($cwd = $environment->getCwd()){
            $line->prepend($cwd->getAbsolutePath().'$', 'path');
        }
    }
}