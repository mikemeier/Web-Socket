<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;

abstract class AbstractUserCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        if(!$console->getClient()->getUser()){
            $console->write($this->getName() .': command not found', 'error');
            return;
        }
        $this->doExecute($input, $console);
    }

    /**
     * @param User $user
     * @return Directory
     */
    protected function getHomeDirectory(User $user)
    {
        return $this->getDirectoryRepository()->getHomeDirectory($user);
    }

    /**
     * @param Console $console
     * @return User
     */
    protected function getUser(Console $console)
    {
        return $console->getClient()->getUser();
    }

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    abstract protected function doExecute(InputInterface $input, Console $console);
}