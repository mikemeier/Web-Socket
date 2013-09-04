<?php

namespace mikemeier\ConsoleGame\Network\Service;

use mikemeier\ConsoleGame\Network\Exception\OutOfIpsException;
use mikemeier\ConsoleGame\Network\Ip\Ip;

class Dhcp
{
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
     * @param string $start
     * @param string $end
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
        return $this->ips[] = new Ip(long2ip($newIp));
    }

    /**
     * @return Ip[]
     */
    public function getIps()
    {
        return $this->ips;
    }
}