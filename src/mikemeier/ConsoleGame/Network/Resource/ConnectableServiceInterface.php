<?php

namespace mikemeier\ConsoleGame\Network\Resource;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Console\Type\ConsoleInterface;

interface ConnectableServiceInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return bool
     */
    public function requireLogin();

    /**
     * @return bool
     */
    public function allowLogin();

    /**
     * @return int
     */
    public function getPort();

    /**
     * @param Console $clientConsole
     * @return ConsoleInterface
     */
    public function getConsole(Console $clientConsole);
}