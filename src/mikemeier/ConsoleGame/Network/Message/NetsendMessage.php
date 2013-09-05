<?php

namespace mikemeier\ConsoleGame\Network\Message;

use mikemeier\ConsoleGame\Network\Ip\Ip;

class NetsendMessage
{
    /**
     * @var Ip
     */
    protected $sender;

    /**
     * @var string
     */
    protected $text;

    /**
     * @param Ip $sender
     * @param string $text
     */
    public function __construct(Ip $sender, $text)
    {
        $this->sender = $sender;
        $this->text = $text;
    }

    /**
     * @return Ip
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }
}