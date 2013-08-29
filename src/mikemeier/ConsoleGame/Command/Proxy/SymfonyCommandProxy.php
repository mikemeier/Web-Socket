<?php

namespace mikemeier\ConsoleGame\Command\Proxy;

use mikemeier\ConsoleGame\Command\CommandInterface;
use Symfony\Component\Console\Command\Command;

class SymfonyCommandProxy extends Command
{
    /**
     * @var CommandInterface
     */
    protected $command;

    /**
     * @param CommandInterface $command
     */
    public function __construct(CommandInterface $command)
    {
        $this->command = $command;
        $this->setName($command->getName());
        $this->setAliases($command->getAliases());
        $this->setDefinition($command->getInputDefinition());
    }
}