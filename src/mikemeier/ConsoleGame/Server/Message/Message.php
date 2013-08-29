<?php

namespace mikemeier\ConsoleGame\Server\Message;

class Message implements \JsonSerializable
{
    /**
     * @var string
     */
    protected $event;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @param string $event
     * @param array $arguments
     */
    public function __construct($event, array $arguments = array())
    {
        $this->event = $event;
        $this->arguments = $arguments;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param int $index
     * @return mixed
     */
    public function getArgument($index)
    {
        return isset($this->arguments[$index]) ? $this->arguments[$index] : null;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array(
            'event' => $this->event,
            'arguments' => $this->arguments
        );
    }
}