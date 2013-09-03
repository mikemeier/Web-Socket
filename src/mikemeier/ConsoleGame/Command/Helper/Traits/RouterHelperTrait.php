<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\RouterHelper;

trait RouterHelperTrait
{
    /**
     * @return RouterHelper
     */
    public function getRouterHelper(){
        return $this->getHelper('router');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
