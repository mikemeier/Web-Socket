<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Network\Router;

class RouterHelper extends AbstractHelper
{
    /**
     * @var Router
     */
    protected $router;

    /**
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }
}