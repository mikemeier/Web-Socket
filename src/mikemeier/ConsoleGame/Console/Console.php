<?php

namespace mikemeier\ConsoleGame\Console;

use mikemeier\ConsoleGame\Command\AutocompletableCommandInterface;
use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Command\Proxy\SymfonyCommandProxy;
use mikemeier\ConsoleGame\Output\Line\Decorator\DecoratorInterface;
use mikemeier\ConsoleGame\Output\Line\Line;
use mikemeier\ConsoleGame\Output\Line\LineFormatter;
use mikemeier\ConsoleGame\Server\Client\Client;
use mikemeier\ConsoleGame\Server\Message\Message;
use Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

class Console
{
    /**
     * @var Client
     */
    protected $client;

    /**
     * @var LineFormatter
     */
    protected $formatter;

    /**
     * @var CommandInterface[]
     */
    protected $commands;

    /**
     * @var DecoratorInterface[]
     */
    protected $decorators;

    /**
     * @var History
     */
    protected $history;

    /**
     * @param Client $client
     * @param array $commands
     * @param array $decorators
     */
    public function __construct(Client $client, array $commands, array $decorators)
    {
        $client->setConsole($this);

        $this->formatter = new LineFormatter();
        $this->history = new History();
        $this->client = $client;

        foreach($commands as $command){
            $this->addCommand($command);
        }

        usort($decorators, function(DecoratorInterface $a, DecoratorInterface $b){
            return $a->getPriority() > $b->getPriority();
        });

        foreach($decorators as $decorator){
            $this->addDecorator($decorator);
        }
    }

    /**
     * @param DecoratorInterface $decorator
     * @return $this
     */
    protected function addDecorator(DecoratorInterface $decorator)
    {
        $this->decorators[] = $decorator;
        return $this;
    }

    /**
     * @param CommandInterface $command
     * @return $this
     */
    protected function addCommand(CommandInterface $command)
    {
        foreach(array_merge(array($command->getName()), $command->getAliases()) as $name){
            $this->commands[strtolower($name)] = $command;
        }
        return $this;
    }

    /**
     * @param string $input
     * @return $this
     */
    public function cancel($input)
    {
        if($command = $this->getClient()->getEnvironment()->getInteractiveCommand()){
            $command->onCancel($this, $input);
            return $this;
        }

        return $this;
    }

    /**
     * @param int $int
     * @return $this
     */
    public function history($int)
    {
        $this->history->addPosition($int);
        if(false !== $text = $this->history->current()){
            $this->sendInputValue($text);
        }
        return $this;
    }

    /**
     * @param string $input
     * @return $this
     */
    public function tab($input)
    {
        if($command = $this->getClient()->getEnvironment()->getInteractiveCommand()){
            $command->onTab($this, $input);
            return $this;
        }

        if(!$input){
            $this->processCommand($this->getCommand('list'));
            return $this;
        }

        $explode = explode(" ", $input);
        $name = isset($explode[0]) ? strtolower($explode[0]) : null;

        if(
            ($command = $this->getCommand($name)) &&
            $command instanceof AutocompletableCommandInterface &&
            $command->isAvailable($this)
        ){
            if(false !== $autocomplete = $command->autocomplete(implode(" ", array_slice($explode, 1)), $this)){
                $this->sendInputValue($command->getName().' '.$autocomplete);
            }
            return $this;
        }

        // Some data given in input
        if(count($explode) > 1){
            return $this;
        }

        /** @var CommandInterface[] $commands */
        $commands = array();
        foreach($this->getCommands() as $cmdName => $command){
            if($command->isAvailable($this) && strtolower(substr($cmdName, 0, strlen($name))) == strtolower($name)){
                $commands[] = $command;
            }
        }

        $count = count($commands);
        if($count == 1){
            $this->sendInputValue($commands[0]->getName().' ');
            return $this;
        }

        if($count > 1){
            $this->write('', null, true);
            foreach($commands as $command){
                $this->write(' * '. $command->getName(), 'description');
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function writeEmptyDecoratedLine()
    {
        $this->write('', null, true);
        return $this;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function sendInputValue($value)
    {
        $this->getClient()->send(new Message('inputvalue', array($value)));
        return $this;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function sendInputStealth($flag)
    {
        $this->getClient()->send(new Message('inputstealth', array((bool)$flag)));
        return $this;
    }

    /**
     * @param string $input
     * @return $this
     */
    public function process($input)
    {
        if($command = $this->getClient()->getEnvironment()->getInteractiveCommand()){
            $command->onInput($this, $input);
            return $this;
        }

        if(!$input){
            return $this;
        }

        $this->history->add($input);

        $explode = explode(" ", $input);
        $name = isset($explode[0]) ? strtolower($explode[0]) : null;
        $inputWithoutName = implode(" ", array_slice($explode, 1));

        if(!$command = $this->getCommand($name)){
            $this->writeFeedback($input);
            $this->writeCommandNotFound($name);
            return $this;
        }

        $this->processCommand($command, new StringInput($inputWithoutName), true, $input);

        return $this;
    }

    /**
     * @param string $command
     * @return $this
     */
    public function writeCommandNotFound($command)
    {
        $this->write($command .': command not found', 'error');
        return $this;
    }

    /**
     * @return CommandInterface[]
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param string $name
     * @return CommandInterface
     */
    public function getCommand($name)
    {
        return isset($this->commands[$name]) ? $this->commands[$name] : null;
    }

    /**
     * @param string $feedback
     * @return $this
     */
    protected function writeFeedback($feedback)
    {
        $this->write($feedback, array('feedback'), true);
        return $this;
    }

    /**
     * @param CommandInterface $command
     * @param InputInterface $input
     * @param bool $describeIfNotValid
     * @param string $feedback
     * @return $this
     */
    public function processCommand(CommandInterface $command, InputInterface $input = null, $describeIfNotValid = false, $feedback = null)
    {
        if(!$command->isAvailable($this)){
            $this->writeCommandNotFound($command->getName());
            return $this;
        }

        $input = $input ?: new StringInput('');

        try {
            $input->bind($command->getInputDefinition());
            $input->validate();
            if(false !== $feedback = $command->getFeedback($input, $feedback)){
                $this->writeFeedback($feedback);
            }
        }catch(\Exception $e){
            if($describeIfNotValid == true){
                try {
                    if(false !== $feedback = $command->getFeedback($input, $feedback)){
                        $this->writeFeedback($feedback);
                    }
                }catch(\Exception $e){ }
                $this->write('Invalid command call', 'error');
                $this->describe($command);
            }
            return $this;
        }

        $command->execute($input, $this);

        return $this;
    }

    /**
     * @param CommandInterface $command
     * @return $this
     */
    public function describe(CommandInterface $command)
    {
        if($description = $command->getDescription()){
            $this->write('Description:', 'description');
            $this->write(' '.$command->getDescription(), 'description');
        }

        $descriptor = new TextDescriptor();
        $output = new BufferedOutput();
        $descriptor->describe($output, new SymfonyCommandProxy($command));

        foreach(array_slice(explode("\n", $output->fetch()), 0, -2) as $line){
            $this->write($line, 'description');
        }

        return $this;
    }

    /**
     * @param Line $line
     * @return Console
     */
    public function writeLine(Line $line)
    {
        $this->writeLines(array($line));
        return $this;
    }

    /**
     * @param Line $line
     * @return ModifiableLine
     */
    public function writeModifiableLine(Line $line)
    {

    }

    /**
     * @param string $text
     * @param string|array $style
     * @param bool $decorated
     * @return $this
     */
    public function write($text, $style = null, $decorated = false)
    {
        $this->writeLine(new Line($text, $style, $decorated));
        return $this;
    }

    /**
     * @param Line[] $lines
     * @return $this
     */
    public function writeLines(array $lines)
    {
        $formatted = array();
        foreach($lines as $line){
            $formatted[] = $this->format($line);
        }
        $this->getClient()->send(new Message('output', $formatted));
        return $this;
    }

    /**
     * @throws \Exception
     * @return Client
     */
    public function getClient()
    {
        if(!$this->client){
            throw new \Exception("Client not set");
        }
        return $this->client;
    }

    /**
     * @param Line $line
     * @return string
     */
    protected function format(Line $line)
    {
        if($line->isDecorated()){
            foreach($this->decorators as $decorator){
                $decorator->decorate($line, $this);
            }
        }
        return $this->formatter->format($line);
    }
}