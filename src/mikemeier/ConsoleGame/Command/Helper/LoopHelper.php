<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use React\EventLoop\LoopInterface;

class LoopHelper extends AbstractHelper
{
    /**
     * @var \Closure
     */
    protected $getLoop;

    /**
     * @param callable $getLoop
     */
    public function __construct(\Closure $getLoop)
    {
        $this->getLoop = $getLoop;
    }

    /**
     * @return LoopInterface
     */
    public function getLoop()
    {
        $getLoop = $this->getLoop;
        return $getLoop();
    }
}