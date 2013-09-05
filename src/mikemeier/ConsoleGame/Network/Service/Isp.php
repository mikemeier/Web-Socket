<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Service\Traits\IpResourceBindingTrait;

class Isp
{
    use IpResourceBindingTrait;

    /**
     * @param Dhcp $dhcp
     * @param Dns $dns
     */
    public function __construct(Dhcp $dhcp, Dns $dns)
    {
        $this->setDhcp($dhcp);
        $this->setDns($dns);
    }
}