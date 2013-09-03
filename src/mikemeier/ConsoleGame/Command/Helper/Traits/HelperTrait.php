<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\HelperInterface;

trait HelperTrait
{
    /**
     * @var array
     */
    protected $helpers = array();

    /**
     * @param HelperInterface $helper
     * @return $this
     */
    protected function addHelper(HelperInterface $helper)
    {
        $this->helpers[strtolower($helper->getName())] = $helper;
        return $this;
    }

    /**
     * @param string $name
     * @throws \Exception
     * @return HelperInterface
     */
    protected function getHelper($name)
    {
        $name = strtolower($name);
        if(isset($this->helpers[$name])){
            return $this->helpers[$name];
        }
        throw new \Exception("Helper $name not found");
    }
}