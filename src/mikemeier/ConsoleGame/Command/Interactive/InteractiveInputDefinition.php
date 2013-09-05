<?php

namespace mikemeier\ConsoleGame\Command\Interactive;

use Symfony\Component\Console\Input\InputDefinition;

class InteractiveInputDefinition
{
    /**
     * @var InputDefinition
     */
    protected $definition;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param InputDefinition $definition
     * @param callable $callable
     * @throws \Exception
     */
    public function __construct(InputDefinition $definition, $callable)
    {
        $this->definition = $definition;
        $this->callable = $callable;
        if(!is_callable($callable)){
            throw new \Exception("Callable is not callable");
        }
    }

    /**
     * @return InputDefinition
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}