<?php

namespace mikemeier\ConsoleGame\Network;

class Router
{
    /**
     * @var Dhcp
     */
    protected $dhcp;

    /**
     * @var Ip
     */
    protected $ip;

    /**
     * @param Dhcp $dhcp
     * @param Ip $ip
     */
    public function __construct(Dhcp $dhcp, Ip $ip)
    {
        $this->dhcp = $dhcp;
        $this->ip = $ip;
    }

    /**
     * @return Ip
     */
    public function getIp()
    {
        return $this->dhcp->getNewIp();
    }
}