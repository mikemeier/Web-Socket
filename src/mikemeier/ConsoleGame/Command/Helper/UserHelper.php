<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Server\Message\Message;

class UserHelper extends AbstractHelper
{
    /**
     * @param Console $console
     * @param EnvironmentHelper $environment
     * @return $this
     */
    public function logoutUser(Console $console, EnvironmentHelper $environment)
    {
        $console->getClient()
            ->setUser(null)
            ->send(new Message('loggedout'))
        ;
        $environment->setCwd($console, null);
        return $this;
    }

    /**
     * @param Console $console
     * @param User $user
     * @param Directory $directory
     * @param EnvironmentHelper $environment
     * @return $this
     */
    public function loginUser(Console $console, User $user, Directory $directory, EnvironmentHelper $environment)
    {
        $console->getClient()
            ->setUser($user)
            ->send(new Message('loggedin', array($user->getUsername())))
        ;
        $environment->setCwd($console, $directory);
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