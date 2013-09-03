<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\FeedbackHelper;

trait FeedbackHelperTrait
{
    /**
     * @return FeedbackHelper
     */
    public function getFeedbackHelper(){
        return $this->getHelper('feedback');
    }

    /**
     * @param string $name
     * @return object
     */
    abstract protected function getHelper($name);
}
