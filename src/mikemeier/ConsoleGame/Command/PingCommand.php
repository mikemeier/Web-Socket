<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\InteractiveCommandTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\Dns\DnsBinding;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class PingCommand extends AbstractCommand implements InteractiveCommandInterface
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
        $resourceName = $input->getArgument('resource');
        $router = $this->getRouterHelper()->getRouter();
        $binding = null;

        if($ip = filter_var($resourceName, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
            if(!$binding = $router->getBindingByIp($ip)){
                $this->notFound($console, $resourceName);
                return $this;
            }
        }

        if(!$binding && !$binding = $router->getBindingByName($resourceName)){
            $this->notFound($console, $resourceName);
            return $this;
        }

        $console->write('Ping '. $binding->getResource()->getName().' ('. $binding->getIp() .')');
        $this->loopPeriodic($console, 1, function()use($console, $binding){
            $this->ping($console, $binding);
        });

        return $this;
    }

    /**
     * @param Console $console
     * @param string $resourceName
     */
    protected function notFound(Console $console, $resourceName)
    {
        $console->write("cannot resolve $resourceName: Unknown host", 'error');
    }

    /**
     * @param Console $console
     * @param DnsBinding $binding
     */
    public function ping(Console $console, DnsBinding $binding)
    {
        $console->write('64 bytes from '. $binding->getIp() .': <success>ok</success>');
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(new InputArgument('resource', InputArgument::REQUIRED, 'Destination')));
    }
}