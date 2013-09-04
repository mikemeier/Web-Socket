<?php

namespace mikemeier\ConsoleGame\Network\Dns;

use mikemeier\ConsoleGame\Network\ResourceInterface;
use mikemeier\ConsoleGame\Network\Ip;

class Dns
{
    /**
     * @var DnsBinding[]
     */
    protected $bindings = array();

    /**
     * @param string $name
     * @return DnsBinding
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
     * @return DnsBinding
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
     * @param ResourceInterface $resource
     * @param Ip $ip
     * @return bool
     */
    public function addBinding(ResourceInterface $resource, Ip $ip)
    {
        $this->bindings[] = new DnsBinding($resource, $ip);
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