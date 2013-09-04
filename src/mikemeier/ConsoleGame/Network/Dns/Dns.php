<?php

namespace mikemeier\ConsoleGame\Network\Dns;

use mikemeier\ConsoleGame\Network\Ip\Ip;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpDnsResourceBinding;
use mikemeier\ConsoleGame\Network\Resource\DnsResourceInterface;

class Dns
{
    /**
     * @var IpDnsResourceBinding[]
     */
    protected $bindings = array();

    /**
     * @param string $name
     * @return IpDnsResourceBinding
     */
    public function getBindingByName($name)
    {
        foreach($this->bindings as $binding){
            if($binding->getResource()->getResourceName() == $name){
                return $binding;
            }
        }
        return null;
    }

    /**
     * @param string $ip
     * @return IpDnsResourceBinding
     */
    public function getBindingByIp($ip)
    {
        foreach($this->bindings as $binding){
            if($binding->getIp()->getIp() == $ip){
                return $binding;
            }
        }
        return null;
    }

    /**
     * @param DnsResourceInterface $resource
     * @param Ip $ip
     * @return bool
     */
    public function addBinding(DnsResourceInterface $resource, Ip $ip)
    {
        $this->bindings[] = new IpDnsResourceBinding($resource, $ip);
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function removeBindingByResourceName($name)
    {
        foreach($this->bindings as $key => $binding){
            if($binding->getResource()->getResourceName() == $name){
                unset($this->bindings[$key]);
            }
        }
        return $this;
    }
}