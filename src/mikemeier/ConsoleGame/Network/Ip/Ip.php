<?php

namespace mikemeier\ConsoleGame\Network\Ip;

use mikemeier\ConsoleGame\Network\Service\Dhcp;

class Ip
{
    /**
     * @var Dhcp
     */
    protected $dhcp;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @param Dhcp $dhcp
     * @param string $ip
     */
    public function __construct(Dhcp $dhcp, $ip)
    {
        $this->dhcp = $dhcp;
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

    /**
     * @return Dhcp
     */
    public function getDhcp()
    {
        return $this->dhcp;
    }
}