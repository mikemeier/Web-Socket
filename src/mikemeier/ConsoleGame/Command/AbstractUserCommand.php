<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;

abstract class AbstractUserCommand extends AbstractCommand
{
    /**
     * @param Console $console
     * @return bool
     */
    public function isAvailable(Console $console)
    {
        return $this->hasUser($console);
    }

    /**
     * @param string $username
     * @return Directory
     */
    protected function getHomeDirectory($username)
    {
        return $this->getDirectoryRepository()->getHomeDirectory($username);
    }

    /**
     * @param Console $console
     * @return bool
     */
    protected function hasUser(Console $console)
    {
        return (bool)$console->getClient()->getUser();
    }

    /**
     * @param Console $console
     * @return User
     */
    protected function getUser(Console $console)
    {
        return $console->getClient()->getUser();
    }
}