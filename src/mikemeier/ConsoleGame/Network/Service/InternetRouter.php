<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Ip\Ip;
use mikemeier\ConsoleGame\Network\Service\Dhcp;

class InternetRouter extends Router
{
    /**
     * @var Isp
     */
    protected $isp;

    /**
     * @var Ip
     */
    protected $wanIp;

    /**
     * @param Isp $isp
     * @param Dhcp $dhcp
     * @param Dns $dns
     */
    public function __construct(Isp $isp, Dhcp $dhcp, Dns $dns)
    {
        $this->isp = $isp;
        $this->wanIp = $isp->getNewIp($this);
        parent::__construct($dhcp, $dns);
    }

    /**
     * @return Ip
     */
    public function getWanIp()
    {
        return $this->wanIp;
    }
}