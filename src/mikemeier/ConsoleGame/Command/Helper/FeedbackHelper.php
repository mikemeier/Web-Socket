<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use mikemeier\ConsoleGame\Command\CommandInterface;
use Symfony\Component\Console\Input\InputInterface;

class FeedbackHelper extends AbstractHelper
{
    /**
     * @param CommandInterface $command
     * @param InputInterface $input
     * @param array $argumentsReplace
     * @return string
     */
    public function prepareFeedback(CommandInterface $command, InputInterface $input, array $argumentsReplace = array())
    {
        $feedback = array($command->getName());
        $definition = $command->getInputDefinition();

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