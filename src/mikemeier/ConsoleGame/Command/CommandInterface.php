<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\DependencyInjection\ContainerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;

interface CommandInterface
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console);

    /**
     * @param ContainerInterface $container
     * @return CommandInterface
     */
    public function setContainer(ContainerInterface $container);

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null);

    /**
     * @return InputDefinition
     */
    public function getInputDefinition();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getAliases();
}