<?php

namespace mikemeier\ConsoleGame\Network\Resource;

interface DnsResourceInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getResourceName();
}