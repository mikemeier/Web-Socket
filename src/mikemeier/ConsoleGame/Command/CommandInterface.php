<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

interface CommandInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getAliases();

    /**
     * @param Console $console
     * @return bool
     */
    public function isAvailable(Console $console);

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface
     */
    public function execute(InputInterface $input, Console $console);

    /**
     * @return InputDefinition
     */
    public function getInputDefinition();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null);

    /**
     * @return string
     */
    public function __toString();
}