<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Network;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\CommandInterface;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Output\Line\Helper\SameLengthLinesHelper;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputInterface;

class IpconfigCommand extends AbstractCommand
{
    use EnvironmentHelperTrait;
    use UserCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface
     */
    public function execute(InputInterface $input, Console $console)
    {
        if(!$ip = $this->getEnvironmentHelper()->getEnvironment($console)->getIp()){
            $console->write('No IP', 'error');
            return $this;
        }

        $console->write('');
        $console->write('IP-Configuration');
        $console->write('');

        $lines = array();

        $line = new Line();
        $line->add('Your DNS Name');
        $line->add($this->getUserHelper()->getUsername($console));
        $lines[] = $line;

        $line = new Line();
        $line->add('IP-Address');
        $line->add($ip);
        $lines[] = $line;

        $line = new Line();
        $line->add('Standard-Gateway');
        $line->add($ip->getDhcp()->getRouter()->getLanIp());
        $lines[] = $line;

        foreach(SameLengthLinesHelper::modify($lines) as $line){
            $console->writeLine($line);
        }

        $console->write('');
    }
}