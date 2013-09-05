<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Network\Exception\OutOfIpsException;
use mikemeier\ConsoleGame\Network\Ip\Ip;

class Dhcp
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var int
     */
    protected $start;

    /**
     * @var int
     */
    protected $end;

    /**
     * @var int
     */
    protected $lastIp;

    /**
     * @var Ip[]
     */
    protected $ips;

    /**
     * @param $start
     * @param $end
     */
    public function __construct($start, $end)
    {
        $this->start = ip2long($start);
        $this->end = ip2long($end);
    }

    /**
     * @throws OutOfIpsException
     * @return Ip
     */
    public function getNewIp()
    {
        $newIp = $this->lastIp ? $this->lastIp+1 : $this->start;
        if($newIp > $this->end){
            throw new OutOfIpsException();
        }
        $this->lastIp = $newIp;
        return $this->ips[] = new Ip($this, long2ip($newIp));
    }

    /**
     * @return Ip[]
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @param Router $router
     * @return Dhcp
     */
    public function setRouter(Router $router)
    {
        $this->router = $router;
        return $this;
    }
}