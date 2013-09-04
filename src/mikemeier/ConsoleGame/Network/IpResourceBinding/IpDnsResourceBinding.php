<?php

namespace mikemeier\ConsoleGame\Network\IpResourceBinding;

use mikemeier\ConsoleGame\Network\Ip\Ip;
use mikemeier\ConsoleGame\Network\Resource\DnsResourceInterface;

class IpDnsResourceBinding extends IpResourceBinding
{
    /**
     * @param DnsResourceInterface $resource
     * @param Ip $ip
     */
    public function __construct(DnsResourceInterface $resource, Ip $ip)
    {
        parent::__construct($resource, $ip);
    }

    /**
     * @return DnsResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }
}