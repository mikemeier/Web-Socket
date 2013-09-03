<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\EntityManagerHelper;

trait EntityManagerHelperTrait
{
    /**
     * @return EntityManagerHelper
     */
    public function getEntityManagerHelper(){
        return $this->getHelper('entitymanager');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
