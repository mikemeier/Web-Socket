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
            $str .= '<span class="'. implode(" ", $part->getStyles()) .'">'. $part->getText() .'</span>';
        }
        return $str;
    }
}