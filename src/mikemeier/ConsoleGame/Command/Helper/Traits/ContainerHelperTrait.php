<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\ContainerHelper;

trait ContainerHelperTrait
{
    /**
     * @return ContainerHelper
     */
    public function getContainerHelper(){
        return $this->getHelper('container');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
