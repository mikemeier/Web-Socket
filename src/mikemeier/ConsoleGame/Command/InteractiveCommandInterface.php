<?php

namespace mikemeier\ConsoleGame\Command;

interface InteractiveCommandInterface extends CommandInterface
{
    /**
     * @return InteractiveCommandInterface
     */
    public function stop();

    /**
     * @param string $input
     * @return InteractiveCommandInterface
     */
    public function interact($input);

    /**
     * @return InteractiveCommandInterface
     */
    public function onBreak();
}