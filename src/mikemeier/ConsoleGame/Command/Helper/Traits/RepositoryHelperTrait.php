<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\RepositoryHelper;

trait RepositoryHelperTrait
{
    /**
     * @return RepositoryHelper
     */
    public function getRepositoryHelper(){
        return $this->getHelper('repository');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
