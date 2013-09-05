<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Network;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\InteractiveCommandInterface;
use mikemeier\ConsoleGame\Command\Traits\InteractiveCommandTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\Resource\ConnectableServiceInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class TelnetCommand extends AbstractCommand implements InteractiveCommandInterface
{
    use RouterHelperTrait;
    use UserCommandTrait;
    use InteractiveCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        $resource = $input->getArgument('resource');
        $router = $this->getRouterHelper()->getRouter();

        if(!$binding = $router->getBinding($resource)){
            $console->write("Cannot resolve $resource: Unknown host", 'error');
            return $this;
        }

        $resource = $binding->getResource();

        $console->write('Try connecting '. $binding->getIp().'...');

        if(!$resource->isOnline()){
            $console->write('No response from '. $binding->getIp() .': <error>offline</error>');
            return $this;
        }

        if(!$resource instanceof ConnectableServiceInterface){
            $console->write('No response from '. $binding->getIp() .': <error>connection not allowed</error>');
            return $this;
        }

        $this->loop($console, mt_rand(2, 5), function()use($console, $resource){
            $this->setEnvironmentData($console, 'resource', $resource);
            $console->write('Username:');
            $console->sendInputStealth(true);
        }, true);

        return $this;
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('resource', InputArgument::REQUIRED, 'Resource to connect')
        ));
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onInput(Console $console, $input)
    {
        /** @var ConnectableServiceInterface $resource */
        if($resource = $this->getEnvironmentData($console, 'resource')){
            $console->write($input);
        }

        return $this;
    }
}