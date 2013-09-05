<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Network;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\Message\NetsendMessage;
use mikemeier\ConsoleGame\Network\Resource\ReceiveNetsendMessageInterface;
use mikemeier\ConsoleGame\Output\Line\Line;
use mikemeier\ConsoleGame\Server\Message\Message;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class NetsendCommand extends AbstractCommand
{
    use EnvironmentHelperTrait;
    use UserCommandTrait;
    use RouterHelperTrait;

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
        if(!$resource instanceof ReceiveNetsendMessageInterface){
            $console->write("Netsend not available on this resource", 'error');
            return $this;
        }

        if(!$resource->isOnline()){
            $console->write('No response from '. $binding->getIp() .': <error>offline</error>');
            return $this;
        }

        $sender = $this->getEnvironmentHelper()->getEnvironment($console)->getIp();
        $message = new NetsendMessage($sender, implode(" ", $input->getArgument('text')));

        if(!$send = $resource->receiveNetsendMessage($message)){
            $console->write("Message not successfull delivered", 'error');
            return $this;
        }

        $console->write("Message successfull delivered", 'success');

        return $this;
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('resource', InputArgument::REQUIRED),
            new InputArgument('text', InputArgument::IS_ARRAY|InputArgument::REQUIRED)
        ));
    }
}