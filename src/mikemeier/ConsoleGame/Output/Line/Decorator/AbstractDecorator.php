<?php

namespace mikemeier\ConsoleGame\Output\Line\Decorator;

abstract class AbstractDecorator implements DecoratorInterface
{
    /**
     * @var int
     */
    protected $priority;

    /**
     * @param int $priority
     */
    public function __construct($priority)
    {
        $this->priority = $priority;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }
}