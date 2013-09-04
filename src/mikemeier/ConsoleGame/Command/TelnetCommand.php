<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
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
     * @return CommandInterface
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

        if(!$resource->isOnline()){
            $console->write('No response from '. $binding->getIp() .': <error>offline</error>');
            return $this;
        }

        if(!$resource instanceof ConnectableServiceInterface){
            $console->write('No response from '. $binding->getIp() .': <error>connection not allowed</error>');
            return $this;
        }

        $this->setInteractive($console);
        $this->setEnvironmentData($console, 'resource', $resource);

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
}