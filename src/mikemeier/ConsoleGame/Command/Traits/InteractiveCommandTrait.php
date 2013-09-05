<?php

namespace mikemeier\ConsoleGame\Command\Traits;

use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\LoopHelperTrait;
use mikemeier\ConsoleGame\Command\InteractiveCommandInterface;
use mikemeier\ConsoleGame\Console\Console;
use React\EventLoop\LoopInterface;
use mikemeier\ConsoleGame\Command\Interactive\InteractiveInputDefinition;
use Symfony\Component\Console\Descriptor\TextDescriptor;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\BufferedOutput;

trait InteractiveCommandTrait
{
    use EnvironmentHelperTrait;
    use LoopHelperTrait;

    /**
     * @var array
     */
    protected $loopSignatures = array();

    /**
     * @param Console $console
     * @return $this
     */
    public function stop(Console $console)
    {
        $this->getEnvironmentHelper()->getEnvironment($console)
            ->setInteractiveCommand(null)
            ->clearData()
        ;

        $console->sendInputStealth(false);

        while($signature = array_pop($this->loopSignatures)){
            $this->getLoop()->cancelTimer($signature);
        }

        return $this;
    }

    /**
     * @param Console $console
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function setEnvironmentData(Console $console, $key, $value)
    {
        $key = 'interactivecommand_'.$this->getName().'_'.$key;
        $this->getEnvironmentHelper()->getEnvironment($console)->setData($key, $value);
        return $this;
    }

    /**
     * @param Console $console
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function getEnvironmentData(Console $console, $key, $default = null)
    {
        $key = 'interactivecommand_'.$this->getName().'_'.$key;
        return $this->getEnvironmentHelper()->getEnvironment($console)->getData($key, $default);
    }

    /**
     * @param Console $console
     * @throws \Exception
     */
    protected function setInteractive(Console $console)
    {
        if(!$this instanceof InteractiveCommandInterface){
            throw new \Exception("Command has to implement InteractiveCommandInterface");
        }
        $this->getEnvironmentHelper()->getEnvironment($console)->setInteractiveCommand($this);
    }

    /**
     * @return LoopInterface
     */
    protected function getLoop()
    {
        return $this->getLoopHelper()->getLoop();
    }

    /**
     * @param Console $console
     * @param int $interval in seconds
     * @param callable $callback
     * @param bool $setInteractive
     * @return string
     */
    protected function loop(Console $console, $interval, $callback, $setInteractive = false)
    {
        if(true === $setInteractive){
            $this->setInteractive($console);
        }
        return $this->loopSignatures[] = $this->getLoop()->addTimer($interval, $callback);
    }

    /**
     * @param Console $console
     * @param int $interval in seconds
     * @param callable $callback
     * @param bool $setInteractive
     * @return string
     */
    protected function loopPeriodic(Console $console, $interval, $callback, $setInteractive = true)
    {
        if(true === $setInteractive){
            $this->setInteractive($console);
        }
        return $this->loopSignatures[] = $this->getLoop()->addPeriodicTimer($interval, $callback);
    }

    /**
     * @return InteractiveInputDefinition[]
     */
    protected function getInteractiveInputDefinitions()
    {
        return array();
    }

    /**
     * @param Console $console
     * @param string $key
     * @return $this
     * @throws \Exception
     */
    protected function activateInteractiveInputDefinition(Console $console, $key)
    {
        $definitions = $this->getInteractiveInputDefinitions();
        if(!isset($definitions[$key])){
            throw new \Exception("InteractiveInputDefinition with key $key not found");
        }
        $this->setEnvironmentData($console, 'interactiveinputdefinition', $definitions[$key]);
        $this->setInteractive($console);
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onCancel(Console $console, $input)
    {
        $this->stop($console);
        $console->writeEmptyDecoratedLine();
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onInput(Console $console, $input)
    {
        /** @var InteractiveInputDefinition $definition */
        if($definition = $this->getEnvironmentData($console, 'interactiveinputdefinition')){
            $input = new StringInput($input);
            try{
                $input->bind($definition->getDefinition());
                $input->validate();
                if($key = call_user_func_array($definition->getCallable(), array($console, $input))){
                    $this->activateInteractiveInputDefinition($console, $key);
                }
            }catch(\Exception $e){
                $console->write('Invalid command call', 'error');
                $console->write('Usage:', 'description');
                $descriptor = new TextDescriptor();
                $output = new BufferedOutput();
                $descriptor->describe($output, $definition->getDefinition());
                foreach(array_slice(explode("\n", $output->fetch()), 0, -2) as $line){
                    $console->write($line, 'description');
                }
            }
        }
        return $this;
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onTab(Console $console, $input)
    {
        return $this;
    }

    /**
     * @return string
     */
    abstract protected function getName();
}