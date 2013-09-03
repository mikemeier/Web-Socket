<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\EnvironmentHelper;

trait EnvironmentHelperTrait
{
    /**
     * @return EnvironmentHelper
     */
    public function getEnvironmentHelper(){
        return $this->getHelper('environment');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
