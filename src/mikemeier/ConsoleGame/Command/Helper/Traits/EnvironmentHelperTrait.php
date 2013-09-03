<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\EnvironmentHelper;

trait EnvironmentHelperTrait
{
    use HelperTrait;

    /**
     * @return EnvironmentHelper
     */
    public function getEnvironmentHelper(){
        return $this->getHelper('entitymanager');
    }
}
