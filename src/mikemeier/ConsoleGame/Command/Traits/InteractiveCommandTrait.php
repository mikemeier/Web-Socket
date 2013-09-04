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
     * @var array
     */
    protected $loops = array();

    /**
     * @param Console $console
     * @return $this
     */
    protected function stop(Console $console)
    {
        $this->getEnvironmentHelper()->getEnvironment($console)->setInteractiveCommand(null);
        while($signature = array_pop($this->loops)){
            $this->getLoop()->cancelTimer($signature);
        }
        return $this;
    }

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
     * @param Console $console
     * @param int $interval in seconds
     * @param callable $callback
     * @return string
     */
    protected function loop(Console $console, $interval, $callback)
    {
        return $this->loops[] = $this->getLoop()->addTimer($interval, $callback);
    }

    /**
     * @param Console $console
     * @param int $interval in seconds
     * @param callable $callback
     * @return string
     */
    protected function loopPeriodic(Console $console, $interval, $callback)
    {
        $this->setInteractive($console);
        return $this->loops[] = $this->getLoop()->addPeriodicTimer($interval, $callback);
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onCancel(Console $console, $input)
    {
        $this->stop($console);
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onInput(Console $console, $input)
    {
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onTab(Console $console, $input)
    {
        return $this;
    }
}