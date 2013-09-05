<?php

namespace mikemeier\ConsoleGame\Network\Service\Traits;

use mikemeier\ConsoleGame\Network\IpResourceBinding\IpResourceBinding;
use mikemeier\ConsoleGame\Network\Resource\ResourceInterface;
use mikemeier\ConsoleGame\Network\Service\Dhcp;
use mikemeier\ConsoleGame\Network\Resource\DnsResourceInterface;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpDnsResourceBinding;
use mikemeier\ConsoleGame\Network\Dns\Dns;
use mikemeier\ConsoleGame\Network\Ip\Ip;

trait IpResourceBindingTrait
{
    /**
     * @var IpResourceBinding[]
     */
    protected $bindings = array();

    /**
     * @var Dhcp
     */
    protected $dhcp;

    /**
     * @var Dns
     */
    protected $dns;

    /**
     * @return Dns
     */
    public function getDns()
    {
        return $this->dns;
    }

    /**
     * @param Dns $dns
     * @return $this
     */
    protected function setDns($dns)
    {
        $this->dns = $dns;
        return $this;
    }

    /**
     * @return Dhcp
     */
    public function getDhcp()
    {
        return $this->dhcp;
    }

    /**
     * @param Dhcp $dhcp
     * @return $this
     */
    protected function setDhcp($dhcp)
    {
        $this->dhcp = $dhcp;
        return $this;
    }

    /**
     * @return IpResourceBinding[]
     */
    protected function getBindings()
    {
        return $this->bindings;
    }

    /**
     * @param IpResourceBinding $binding
     * @return $this
     */
    protected function addBinding(IpResourceBinding $binding)
    {
        $this->bindings[] = $binding;
        return $this;
    }

    /**
     * @param ResourceInterface|DnsResourceInterface $resource if binding on dns required
     * @return Ip
     */
    public function getNewIp(ResourceInterface $resource)
    {
        $ip = $this->getDhcp()->getNewIp();
        $this->addBinding(new IpResourceBinding($resource, $ip));

        if($resource instanceof DnsResourceInterface){
            $this->getDns()->addBinding($resource, $ip);
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
}