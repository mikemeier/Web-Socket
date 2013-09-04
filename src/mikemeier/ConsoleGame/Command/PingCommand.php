<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\InteractiveCommandTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpDnsResourceBinding;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpResourceBinding;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class PingCommand extends AbstractCommand implements InteractiveCommandInterface
{
    use RouterHelperTrait;
    use UserCommandTrait;
    use InteractiveCommandTrait {
        onCancel as baseOnCancel;
    }

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
            $console->write("cannot resolve $resource: Unknown host", 'error');
            return $this;
        }

        $count = $input->getOption('count') ? (int)$input->getOption('count') : false;

        if($binding instanceof IpDnsResourceBinding){
            $console->write('Ping '. $binding->getResource()->getResourceName().' ('. $binding->getIp() .')');
        }else{
            $console->write('Ping '. $binding->getIp());
        }

        $this->setEnvironmentData($console, 'binding', $binding);
        $this->setEnvironmentData($console, 'statistic', array(
            'online' => 0,
            'offline' => 0
        ));

        $this->loopPeriodic($console, 1, function()use($console, $binding, &$count){
            $this->ping($console, $binding);
            if($count !== false && --$count <= 0){
                $this->stop($console);
            }
        });

        return $this;
    }

    /**
     * @param Console $console
     * @param IpResourceBinding $binding
     */
    public function ping(Console $console, IpResourceBinding $binding)
    {
        $statistic = $this->getEnvironmentData($console, 'statistic');

        if($binding->getResource()->isOnline()){
            $console->write('Response from '. $binding->getIp() .': <success>online</success>');
            $statistic['online']++;
        }else{
            $console->write('No response from '. $binding->getIp() .': <error>offline</error>');
            $statistic['offline']++;
        }

        $this->setEnvironmentData($console, 'statistic', $statistic);
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onCancel(Console $console, $input)
    {
        $statistic = $this->getEnvironmentData($console, 'statistic');
        $binding = $this->getEnvironmentData($console, 'binding');

        $this->baseOnCancel($console, $input);

        $name = $binding instanceof IpDnsResourceBinding ? $binding->getResource()->getResourceName() : $binding->getIp()->getIp();
        $console->write('--- '. $name .' ping statistics ---');
        $total = $statistic['online'] + $statistic['offline'];
        $loss = round(100 / $total * $statistic['offline'], 1);
        $console->write("<description>$total packets transmitted</description>, <success>". $statistic['online'] ." packets received</success>, <error>$loss% packet loss</error>");
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('resource', InputArgument::REQUIRED, 'Destination of ping'),
            new InputOption('count', 'c', InputOption::VALUE_REQUIRED, 'Number of pings to send')
        ));
    }
}