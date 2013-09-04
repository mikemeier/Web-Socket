<?php

namespace mikemeier\ConsoleGame\Network\IpResourceBinding;

use mikemeier\ConsoleGame\Network\Resource\ResourceInterface;
use mikemeier\ConsoleGame\Network\Ip\Ip;

class IpResourceBinding
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