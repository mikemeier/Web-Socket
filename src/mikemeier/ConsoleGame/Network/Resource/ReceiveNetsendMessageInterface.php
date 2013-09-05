<?php

namespace mikemeier\ConsoleGame\Network\Resource;

use mikemeier\ConsoleGame\Network\Message\NetsendMessage;

interface ReceiveNetsendMessageInterface extends ResourceInterface
{
    /**
     * @param NetsendMessage $message
     * @return bool
     */
    public function receiveNetsendMessage(NetsendMessage $message);
}