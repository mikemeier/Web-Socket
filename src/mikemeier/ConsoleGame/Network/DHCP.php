<?php

namespace mikemeier\ConsoleGame\Network;

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
     * @param Ip $start
     * @param Ip $end
     */
    public function __construct(Ip $start, Ip $end)
    {
        $this->start = ip2long($start->getIp());
        $this->end = ip2long($end->getIp());
    }

    /**
     * @return Ip
     * @throws OutOfIpsException
     */
    public function getNewIp()
    {
        $newIp = $this->lastIp ? $this->lastIp+1 : $this->start;
        if($newIp > $this->end){
            throw new OutOfIpsException();
        }
        $this->lastIp = $newIp;
        return new Ip(long2ip($newIp));
    }
}