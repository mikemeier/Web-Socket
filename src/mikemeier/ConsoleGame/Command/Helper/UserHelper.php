<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Server\Message\Message;

class UserHelper extends AbstractHelper
{
    use EnvironmentHelperTrait;

    /**
     * @param Console $console
     * @return $this
     */
    public function logoutUser(Console $console)
    {
        $console->getClient()
            ->setUser(null)
            ->send(new Message('loggedout'))
        ;
        $this->getEnvironmentHelper()->setCwd($console, null);
        return $this;
    }

    /**
     * @param Console $console
     * @return string
     */
    public function getUsername(Console $console)
    {
        return $this->getUser($console)->getUsername();
    }

    /**
     * @param Console $console
     * @param User $user
     * @param Directory $directory
     * @return $this
     */
    public function loginUser(Console $console, User $user, Directory $directory)
    {
        $console->getClient()
            ->setUser($user)
            ->send(new Message('loggedin', array($user->getUsername())))
        ;
        $this->getEnvironmentHelper()->setCwd($console, $directory);
        return $this;
    }

    /**
     * @param Console $console
     * @return bool
     */
    public function hasUser(Console $console)
    {
        return (bool)$console->getClient()->getUser();
    }

    /**
     * @param Console $console
     * @return User
     */
    public function getUser(Console $console)
    {
        return $console->getClient()->getUser();
    }
}