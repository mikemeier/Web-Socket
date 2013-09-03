<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Command\Helper\FeedbackHelper;

trait FeedbackHelperTrait
{
    use HelperTrait;

    /**
     * @return FeedbackHelper
     */
    public function getFeedbackHelper(){
        return $this->getHelper('feedback');
    }
}
