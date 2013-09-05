<?php

namespace mikemeier\ConsoleGame\Console\Type;

use mikemeier\ConsoleGame\Console\Menu\Menu;
use Symfony\Component\Console\Input\InputInterface;

class TelnetConsole extends AbstractConsole
{
    /**
     * @var Menu
     */
    protected $menu;

    /**
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @param InputInterface $input
     * @return ConsoleInterface
     */
    public function process(InputInterface $input)
    {

    }
}