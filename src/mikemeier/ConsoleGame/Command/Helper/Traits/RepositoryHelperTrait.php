<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\RepositoryHelper;

trait RepositoryHelperTrait
{
    use HelperTrait;

    /**
     * @return RepositoryHelper
     */
    public function getRepositoryHelper(){
        return $this->getHelper('repository');
    }
}
