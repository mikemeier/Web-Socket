<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\UserHelper;

trait UserHelperTrait
{
    use HelperTrait;

    /**
     * @return UserHelper
     */
    public function getUserHelper(){
        return $this->getHelper('user');
    }
}
