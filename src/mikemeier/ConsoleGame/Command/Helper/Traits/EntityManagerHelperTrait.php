<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\EntityManagerHelper;

trait EntityManagerHelperTrait
{
    use HelperTrait;

    /**
     * @return EntityManagerHelper
     */
    public function getEntityManagerHelper(){
        return $this->getHelper('entitymanager');
    }
}
