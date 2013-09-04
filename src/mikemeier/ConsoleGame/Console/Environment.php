<?php

namespace mikemeier\ConsoleGame\Console;

use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Network\Ip;
use mikemeier\ConsoleGame\Command\InteractiveCommandInterface;

class Environment
{
    /**
     * @var InteractiveCommandInterface
     */
    protected $interactiveCommand = null;

    /**
     * @var Directory
     */
    protected $cwd = null;

    /**
     * @var Ip
     */
    protected $ip;

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

    /**
     * @return InteractiveCommandInterface
     */
    public function getInteractiveCommand()
    {
        return $this->interactiveCommand;
    }

    /**
     * @param InteractiveCommandInterface $interactiveCommand
     * @return Environment
     */
    public function setInteractiveCommand($interactiveCommand)
    {
        $this->interactiveCommand = $interactiveCommand;
        return $this;
    }
}