<?php

namespace mikemeier\ConsoleGame\Network\Resource;

interface ConnectableServiceInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getPassword();

    /**
     * @return bool
     */
    public function requireLogin();

    /**
     * @return bool
     */
    public function allowLogin();
}