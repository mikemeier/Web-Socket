<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class LogoutCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     */
    public function execute(InputInterface $input, Console $console)
    {
        if(!$console->getClient()->getUser()){
            $console->write('Not loggedin', 'error');
            return;
        }
        $this->logoutUser($console);
        $console->write('Bye');
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition();
    }
}