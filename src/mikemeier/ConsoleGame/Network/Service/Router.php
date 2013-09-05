<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Console\Menu\Menu;
use mikemeier\ConsoleGame\Console\Type\ConsoleFactory;
use mikemeier\ConsoleGame\Console\Type\ConsoleInterface;
use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Ip\Ip;
use mikemeier\ConsoleGame\Network\Resource\ConnectableServiceInterface;
use mikemeier\ConsoleGame\Network\Service\Dhcp;
use mikemeier\ConsoleGame\Network\Service\Traits\IpResourceBindingTrait;

class Router implements ConnectableServiceInterface
{
    use IpResourceBindingTrait;

    /**
     * @var Ip
     */
    protected $lanIp;

    /**
     * @param Dhcp $dhcp
     * @param Dns $dns
     */
    public function __construct(Dhcp $dhcp, Dns $dns)
    {
        $this->setDhcp($dhcp);
        $this->setDns($dns);

        $dhcp->setRouter($this);

        $this->lanIp = $this->getNewIp($this);
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