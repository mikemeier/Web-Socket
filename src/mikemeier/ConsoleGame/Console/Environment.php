<?php

namespace mikemeier\ConsoleGame\Console;

use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Network\Ip;

class Environment
{
    /**
     * @var CommandInterface
     */
    protected $pendingCommand = null;

    /**
     * @var Directory
     */
    protected $cwd = null;

    /**
     * @var Ip
     */
    protected $ip;

    /**
     * @return CommandInterface
     */
    public function getPendingCommand()
    {
        return $this->pendingCommand;
    }

    /**
     * @param CommandInterface $pendingCommand
     * @return Environment
     */
    public function setPendingCommand(CommandInterface $pendingCommand)
    {
        $this->pendingCommand = $pendingCommand;
        return $this;
    }

    /**
     * @return Directory
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * @param Directory $cwd
     * @return Environment
     */
    public function setCwd(Directory $cwd = null)
    {
        $this->cwd = $cwd;
        return $this;
    }

    /**
     * @return Ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param Ip $ip
     * @return Environment
     */
    public function setIp(Ip $ip = null)
    {
        $this->ip = $ip;
        return $this;
    }
}