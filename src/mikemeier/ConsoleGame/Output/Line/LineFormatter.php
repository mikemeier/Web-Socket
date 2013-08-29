<?php

namespace mikemeier\ConsoleGame\Output\Line;

class LineFormatter
{
    /**
     * @param Line $line
     * @return string
     */
    public function format(Line $line)
    {
        $str = '';
        foreach($line->getParts() as $part){
            $text = str_replace(" ", "&nbsp;", $part->getText());
            $text = preg_replace('|<([a-z]+)>|', '<span class="$1">', $text);
            $text = preg_replace('|</[a-z]+>|', '</span>', $text);
            $str .= '<span class="'. implode(" ", $part->getStyles()) .'">'. $text .'</span>';
        }
        return $str;
    }
}