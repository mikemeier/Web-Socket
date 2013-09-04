<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\LoopHelper;

trait LoopHelperTrait
{
    /**
     * @return LoopHelper
     */
    public function getLoopHelper(){
        return $this->getHelper('loop');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
