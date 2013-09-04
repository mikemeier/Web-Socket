<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Console\Environment;
use mikemeier\ConsoleGame\Network\Service\Router;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Server\Message\Message;

class UserHelper extends AbstractHelper
{
    /**
     * @param Console $console
     * @param Environment $environment
     * @return $this
     */
    public function logoutUser(Console $console, Environment $environment)
    {
        $console->getClient()
            ->setUser(null)
            ->send(new Message('loggedout'))
        ;
        $environment->setCwd(null);
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
     * @param DirectoryRepository $directoryRepo
     * @param Environment $environment
     * @param Router $router
     * @return $this
     */
    public function loginUser(
        Console $console,
        User $user,
        DirectoryRepository $directoryRepo,
        Environment $environment,
        Router $router
    ){
        $ip = $router->getNewIp($console->getClient());

        $console->getClient()
            ->setUser($user)
            ->send(new Message('loggedin', array($user->getUsername(), $ip->getIp())))
        ;

        $environment
            ->setCwd($directoryRepo->getHomeDirectory($user->getUsername()))
            ->setIp($ip)
        ;

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