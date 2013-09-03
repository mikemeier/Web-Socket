<?php

namespace mikemeier\ConsoleGame\Command\Helper;

abstract class AbstractHelper implements HelperInterface
{
    /**
     * @return string
     */
    public function getName()
    {
        $reflection = new \ReflectionClass($this);
        return strtolower(substr($reflection->getShortName(), 0, -6));
    }
}