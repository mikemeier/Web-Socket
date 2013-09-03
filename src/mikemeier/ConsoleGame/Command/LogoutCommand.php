<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use mikemeier\ConsoleGame\Command\Helper\UserHelper;

class LogoutCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        if(!$console->getClient()->getUser()){
            $console->write('Not loggedin', 'error');
            return $this;
        }

        $this->getHelper('user')->logoutUser($console, $this->getHelper('environment'));
        $console->write('Bye', 'logout');

        return $this;
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition();
    }
}