<?php

namespace mikemeier\ConsoleGame\Network;

use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Dns\DnsBinding;

class Router
{
    /**
     * @var Dhcp
     */
    protected $dhcp;

    /**
     * @var Dns
     */
    protected $dns;

    /**
     * @var Ip
     */
    protected $lanIp;

    /**
     * @var Ip
     */
    protected $wanIp;

    /**
     * @param Dhcp $dhcp
     * @param Dns $dns
     */
    public function __construct(Dhcp $dhcp, Dns $dns)
    {
        $this->dhcp = $dhcp;
        $this->dns = $dns;

        $this->lanIp = $this->getNewIp();
        $this->wanIp = $this->getNewIp();
    }

    /**
     * @param ResourceInterface $resource if binding on dns required
     * @return Ip
     */
    public function getNewIp(ResourceInterface $resource = null)
    {
        $ip = $this->dhcp->getNewIp();
        if($resource){
            $this->dns->addBinding($resource, $ip);
        }
        return $ip;
    }

    /**
     * @param string $ip
     * @return DnsBinding
     */
    public function getBindingByIp($ip)
    {
        return $this->dns->getBindingByIp($ip);
    }

    /**
     * @param string $name
     * @return DnsBinding
     */
    public function getBindingByName($name)
    {
        return $this->dns->getBindingByName($name);
    }

    /**
     * @return Ip
     */
    public function getWanIp()
    {
        return $this->wanIp;
    }

    /**
     * @return Ip
     */
    public function getLanIp()
    {
        return $this->lanIp;
    }
}