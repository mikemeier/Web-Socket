<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Console\Menu\Menu;
use mikemeier\ConsoleGame\Console\Type\ConsoleFactory;
use mikemeier\ConsoleGame\Console\Type\ConsoleInterface;
use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Ip\Ip;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpResourceBinding;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpDnsResourceBinding;
use mikemeier\ConsoleGame\Network\Resource\ConnectableServiceInterface;
use mikemeier\ConsoleGame\Network\Resource\ResourceInterface;
use mikemeier\ConsoleGame\Network\Resource\DnsResourceInterface;
use mikemeier\ConsoleGame\Network\Service\Dhcp;

class Router implements ConnectableServiceInterface
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
     * @var IpResourceBinding[]
     */
    protected $bindings = array();

    /**
     * @param Dhcp $dhcp
     * @param Dns $dns
     */
    public function __construct(Dhcp $dhcp, Dns $dns)
    {
        $dhcp->setRouter($this);
        $this->dhcp = $dhcp;
        $this->dns = $dns;

        $this->lanIp = $this->getNewIp($this);
        $this->wanIp = $this->getNewIp($this);
    }

    /**
     * @param ResourceInterface|DnsResourceInterface $resource if binding on dns required
     * @return Ip
     */
    public function getNewIp(ResourceInterface $resource)
    {
        $ip = $this->dhcp->getNewIp();
        $this->bindings[] = new IpResourceBinding($resource, $ip);

        if($resource instanceof DnsResourceInterface){
            $this->dns->addBinding($resource, $ip);
        }

        return $ip;
    }

    /**
     * @param string $ip
     * @return IpResourceBinding
     */
    public function getBindingByIp($ip)
    {
        foreach($this->bindings as $binding){
            if($binding->getIp()->getIp() == $ip){
                return $binding;
            }
        }
        return $this->dns->getBindingByIp($ip);
    }

    /**
     * @param string $resource
     * @return IpResourceBinding
     */
    public function getBinding($resource)
    {
        if($ip = filter_var($resource, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            return $this->getBindingByIp($ip);
        }
        return $this->getBindingByName($resource);
    }

    /**
     * @param string $name
     * @return IpDnsResourceBinding
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

    /**
     * @return bool
     */
    public function isOnline()
    {
       return mt_rand(1, 100) > 10;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return 'admin';
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return '1234';
    }

    /**
     * @return bool
     */
    public function requireLogin()
    {
        return true;
    }

    /**
     * @return bool
     */
    public function allowLogin()
    {
        return true;
    }

    /**
     * @param Console $clientConsole
     * @return ConsoleInterface
     */
    public function getConsole(Console $clientConsole)
    {
        $menu = new Menu('root');

        return ConsoleFactory::createTelnetConsole($clientConsole, $menu);
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return 22;
    }
}