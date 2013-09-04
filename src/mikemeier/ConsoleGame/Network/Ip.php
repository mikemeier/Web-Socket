<?php

namespace mikemeier\ConsoleGame\Network;

class Ip
{
    /**
     * @var string
     */
    protected $ip;

    /**
     * @param string $ip
     */
    public function __construct($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getIp();
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }
}