<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\ContainerHelper;

trait ContainerHelperTrait
{
    use HelperTrait;

    /**
     * @return ContainerHelper
     */
    public function getContainerHelper(){
        return $this->getHelper('container');
    }
}
