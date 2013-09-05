<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Network;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpDnsResourceBinding;
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

        
    }
}