<?php

namespace mikemeier\ConsoleGame\Command\Traits;

use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\LoopHelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use React\EventLoop\LoopInterface;

trait InteractiveCommandTrait
{
    use EnvironmentHelperTrait;
    use LoopHelperTrait;

    /**
     * @var bool
     */
    protected $running = true;

    /**
     * @param Console $console
     */
    protected function setInteractive(Console $console){
        $this->getEnvironmentHelper()->getEnvironment($console)->setInteractiveCommand($this);
    }

    /**
     * @return LoopInterface
     */
    protected function getLoop()
    {
        return $this->getLoopHelper()->getLoop();
    }

    /**
     * @return bool
     */
    protected function isRunning()
    {
        return $this->running;
    }


    protected function loop()
    {

    }
    /**
     * @return $this
     */
    public function onBreak(Console $console)
    {
        $this->stop($console);
        return $this;
    }

    /**
     * @param Console $console
     * @return $this
     */
    public function stop(Console $console)
    {
        $this->running = false;
        $this->getEnvironmentHelper()->getEnvironment($console)->setInteractiveCommand(null);
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function interact(Console $console, $input)
    {
        return $this;
    }
}