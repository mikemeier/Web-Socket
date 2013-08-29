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
        if(!$this->hasUser($console)){
            $console->write($this->getName() .': command not found', 'error');
            return;
        }
        $this->doExecute($input, $console);
    }

    /**
     * @param Console $console
     */
    protected function writeCwd(Console $console)
    {
        $console->write('', null, true);
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
     * @param string $username
     * @return Directory
     */
    protected function getHomeDirectory($username)
    {
        return $this->getDirectoryRepository()->getHomeDirectory($username);
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