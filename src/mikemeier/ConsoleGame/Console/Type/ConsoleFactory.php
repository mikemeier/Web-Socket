<?php

namespace mikemeier\ConsoleGame\Console\Type;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Console\Menu\Menu;

class ConsoleFactory
{
    /**
     * @param Console $clientConsole
     * @param Menu $menu
     * @return TelnetConsole
     */
    public static function createTelnetConsole(Console $clientConsole, Menu $menu)
    {
        $console = new TelnetConsole($menu);
        $console->setClientConsole($clientConsole);
        return $console;
    }
}