<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputInterface;

class DateCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface|void
     */
    public function execute(InputInterface $input, Console $console)
    {
        $time = new \DateTime();
        $console->write($time->format(\DateTime::RFC850));
        return $this;
    }
}