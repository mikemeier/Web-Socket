<?php

namespace mikemeier\ConsoleGame\DependencyInjection;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    protected $services;

    /**
     * @param array $services
     */
    public function __construct(array $services = array())
    {
        foreach($services as $id => $service){
            $this->set($id, $service);
        }
    }

    /**
     * @param string $id
     * @param mixed $service
     * @return ContainerInterface
     */
    public function set($id, $service)
    {
        $this->services[$id] = $service;
        return $this;
    }

    /**
     * @param string $id
     * @throws \Exception
     * @return mixed
     */
    public function get($id)
    {
        if(!isset($this->services[$id])){
            throw new \Exception("Service $id not found");
        }
        return $this->services[$id];
    }
}