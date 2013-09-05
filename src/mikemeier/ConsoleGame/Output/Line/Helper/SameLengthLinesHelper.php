<?php

namespace mikemeier\ConsoleGame\Output\Line\Helper;

use mikemeier\ConsoleGame\Output\Line\Line;
use mikemeier\ConsoleGame\Output\Line\LinePart;

class SameLengthLinesHelper
{
    /**
     * @param Line[] $lines
     * @param string $fillWith
     * @param string $suffix
     * @param array $parts
     * @return array
     */
    public static function modify(array $lines, $fillWith = '.', $suffix = ':', array $parts = array(0))
    {
        /** @var LinePart[] $longestParts */
        $longestParts = array();

        foreach($lines as $line){
            foreach($line->getParts() as $partKey => $part){
                if(!in_array($partKey, $parts)){
                    continue;
                }
                if(!isset($longestParts[$partKey])){
                    $longestParts[$partKey] = $part;
                    continue;
                }
                if(strlen($longestParts[$partKey]->getText()) < strlen($part->getText())){
                    $longestParts[$partKey] = $part;
                }
            }
        }

        foreach($lines as $line){
            foreach($line->getParts() as $partKey => $part){
                if(!in_array($partKey, $parts)){
                    continue;
                }
                $partLength = strlen($part->getText());
                $longestPartLength = strlen($longestParts[$partKey]->getText());
                $part->setText($part->getText().str_repeat($fillWith, $longestPartLength-$partLength).$suffix);
            }
        }

        return $lines;
    }
}