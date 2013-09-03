<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\UserHelper;

trait UserHelperTrait
{
    /**
     * @return UserHelper
     */
    public function getUserHelper(){
        return $this->getHelper('user');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
