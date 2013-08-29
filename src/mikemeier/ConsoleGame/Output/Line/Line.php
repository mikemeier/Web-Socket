<?php

namespace mikemeier\ConsoleGame\Output\Line;

class Line
{
    /**
     * @var LinePart[]
     */
    protected $parts = array();

    /**
     * @var bool
     */
    protected $decorated = false;

    /**
     * @param string $text
     * @param string $style
     * @param bool $decorated
     */
    public function __construct($text = null, $style = null, $decorated = false)
    {
        $this->decorated = $decorated;
        if($text){
            $this->add($text, $style);
        }
    }

    /**
     * @return bool
     */
    public function isDecorated()
    {
        return $this->decorated;
    }

    /**
     * @return LinePart[]
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * @param LinePart $part
     * @return $this
     */
    public function addPart(LinePart $part)
    {
        $this->parts[] = $part;
        return $this;
    }

    /**
     * @param LinePart $part
     * @return $this
     */
    public function prependPart(LinePart $part)
    {
        array_unshift($this->parts, $part);
        return $this;
    }

    /**
     * @param string $text
     * @param array|string $style
     * @return $this
     */
    public function add($text, $style = null)
    {
        $this->addPart(new LinePart($text, $style));
        return $this;
    }

    /**
     * @param string $text
     * @param array|string $style
     * @return $this
     */
    public function prepend($text, $style = null)
    {
        $this->prependPart(new LinePart($text, $style));
        return $this;
    }
}