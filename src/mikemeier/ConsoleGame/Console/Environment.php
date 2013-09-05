<?php

namespace mikemeier\ConsoleGame\Console;

use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Network\Ip\Ip;
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
     * @var array
     */
    protected $data = array();

    /**
     * @return Directory
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getData($key, $default = null)
    {
        return isset($this->data[$key]) ? $this->data[$key] : $default;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearData()
    {
        $this->data = array();
        return $this;
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
    public function setInteractiveCommand(InteractiveCommandInterface $interactiveCommand = null)
    {
        $this->interactiveCommand = $interactiveCommand;
        return $this;
    }
}