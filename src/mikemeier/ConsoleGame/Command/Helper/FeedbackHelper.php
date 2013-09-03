<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class FeedbackHelper extends AbstractHelper
{
    /**
     * @param InputDefinition $definition
     * @param InputInterface $input
     * @param array $argumentsReplace
     * @return string
     */
    public function prepareFeedback(InputDefinition $definition, InputInterface $input, array $argumentsReplace = array())
    {
        $feedback = array($this->getName());

        foreach($definition->getOptions() as $option){
            $text = '--'.$option->getName();
            if($option->acceptValue()){
                $text .= '='.$input->getOption($option->getName());
            }
            $feedback[] = $text;
        }

        foreach($definition->getArguments() as $argument){
            $argumentName = $argument->getName();
            $feedback[] = isset($argumentsReplace[$argumentName]) ?
                $argumentsReplace[$argumentName] : $input->getArgument($argument->getName());
        }

        return implode(" ", $feedback);
    }
}