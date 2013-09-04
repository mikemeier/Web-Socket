<?php

namespace mikemeier\ConsoleGame\Network\Resource;

interface ResourceInterface
{
    /**
     * @return string
     */
    public function isOnline();
}