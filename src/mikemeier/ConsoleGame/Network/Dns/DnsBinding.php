<?php

namespace mikemeier\ConsoleGame\Network\Dns;

use mikemeier\ConsoleGame\Network\ResourceInterface;
use mikemeier\ConsoleGame\Network\Ip;

class DnsBinding
{
    /**
     * @var ResourceInterface
     */
    protected $resource;

    /**
     * @var Ip
     */
    protected $ip;

    /**
     * @param ResourceInterface $resource
     * @param Ip $ip
     */
    public function __construct(ResourceInterface $resource, Ip $ip)
    {
        $this->resource = $resource;
        $this->ip = $ip;
    }

    /**
     * @return ResourceInterface
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * @return Ip
     */
    public function getIp()
    {
        return $this->ip;
    }
}