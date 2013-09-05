<?php

namespace mikemeier\ConsoleGame\Command\Concrete\User;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class LogoutCommand extends AbstractCommand
{
    use UserHelperTrait;
    use EnvironmentHelperTrait;

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

        $this->getUserHelper()->logoutUser($console, $this->getEnvironmentHelper()->getEnvironment($console));
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