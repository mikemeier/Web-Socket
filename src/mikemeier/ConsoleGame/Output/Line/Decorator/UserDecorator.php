<?php

namespace mikemeier\ConsoleGame\Output\Line\Decorator;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Line;

class UserDecorator extends AbstractDecorator
{
    /**
     * @param Line $line
     * @param Console $console
     * @return $this|DecoratorInterface
     */
    public function decorate(Line $line, Console $console)
    {
        $username = ($user = $console->getClient()->getUser()) ? $user->getUsername() : 'anonymous';
        $line->prepend($username.'@'.gethostname().':', array('user'));
        return $this;
    }
}