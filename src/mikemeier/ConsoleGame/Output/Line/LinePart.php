<?php

namespace mikemeier\ConsoleGame\Output\Line;

class LinePart
{
    /**
     * @var string
     */
    protected $text;

    /**
     * @var array
     */
    protected $styles = array();

    /**
     * @param string $text
     * @param array|string $style
     */
    public function __construct($text, $style = null)
    {
        $this->text = $text;
        if($style){
            $this->styles = is_array($style) ? $style : array($style);
        }
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->styles;
    }

    /**
     * @param string $text
     * @return LinePart
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param array $styles
     * @return LinePart
     */
    public function setStyles(array $styles)
    {
        $this->styles = $styles;
        return $this;
    }
}