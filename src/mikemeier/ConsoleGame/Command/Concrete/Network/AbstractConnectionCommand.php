<?php

namespace mikemeier\ConsoleGame\Command\Concrete\Network;

use mikemeier\ConsoleGame\Command\AbstractCommand;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Interactive\InteractiveInputDefinition;
use mikemeier\ConsoleGame\Command\InteractiveCommandInterface;
use mikemeier\ConsoleGame\Command\Traits\InteractiveCommandTrait;
use mikemeier\ConsoleGame\Command\Traits\UserCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\Resource\ConnectableServiceInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use mikemeier\ConsoleGame\Network\IpResourceBinding\IpResourceBinding;
use Symfony\Component\Console\Input\StringInput;
use mikemeier\ConsoleGame\Console\Type\ConsoleInterface;

abstract class AbstractConnectionCommand extends AbstractCommand implements InteractiveCommandInterface
{
    use RouterHelperTrait;
    use UserCommandTrait;
    use InteractiveCommandTrait{
        onCancel as baseOnCancel;
    }

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

        if(!$resource instanceof ConnectableServiceInterface OR !$resource->allowLogin()){
            $console->write('No response from '. $binding->getIp() .': <error>connection not allowed</error>');
            return $this;
        }

        if(!$resource->isOnline() OR !$resource->getPort() == $input->getOption('port')){
            $console->write('No response from '. $binding->getIp() .': <error>offline</error>');
            $this->stop($console);
            return $this;
        }

        $this->setEnvironmentData($console, 'binding', $binding);

        $this->loop($console, mt_rand(2, 5), function()use($console, $resource){
            if(!$resource->requireLogin()){
                $console->write('Connection established', 'success');
                $this->activateInteractiveInputDefinition($console, 'connection');
                return;
            }
            $console->write('Username:', 'question');
            $this->activateInteractiveInputDefinition($console, 'username');
        }, true);

        return $this;
    }

    /**
     * @param Console $console
     * @param InputInterface $input
     * @return string
     */
    public function onUsername(Console $console, InputInterface $input)
    {
        $this->setEnvironmentData($console, 'username', $input->getArgument('username'));
        $console->write('Password:', 'question');
        $console->sendInputStealth(true);
        return 'password';
    }

    /**
     * @param Console $console
     * @param InputInterface $input
     * @return string
     */
    public function onPassword(Console $console, InputInterface $input)
    {
        $console->sendInputStealth(false);

        $username = $this->getEnvironmentData($console, 'username');
        $password = $input->getArgument('password');

        /** @var IpResourceBinding $binding */
        $binding = $this->getEnvironmentData($console, 'binding');

        /** @var ConnectableServiceInterface $resource */
        $resource = $binding->getResource();

        if($username != $resource->getUsername() || $password != $resource->getPassword()){
            $console->write('Username and/or password wrong', 'error');
            $console->write('Username:', 'question');
            return 'username';
        }

        $console->write('Connection established', 'success');
        return 'connection';
    }

    /**
     * @param Console $console
     * @param InputInterface $input
     */
    public function onConnection(Console $console, InputInterface $input)
    {
        if(!$subConsole = $this->getEnvironmentData($console, 'subconsole')){
            /** @var IpResourceBinding $binding */
            $binding = $this->getEnvironmentData($console, 'binding');

            /** @var ConnectableServiceInterface $resource */
            $resource = $binding->getResource();

            $subConsole = $resource->getConsole($console);

            $this->setEnvironmentData($console, 'subconsole', $subConsole);
        }

        $stringInput = implode(" ", $input->getArgument('input'));
        $subConsole->process(new StringInput($stringInput));
    }

    /**
     * @return array
     */
    public function getInteractiveInputDefinitions()
    {
        return array(
            'username' => new InteractiveInputDefinition(
                new InputDefinition(array(new InputArgument('username'))),
                array($this, 'onUsername')
            ),
            'password' => new InteractiveInputDefinition(
                new InputDefinition(array(new InputArgument('password'))),
                array($this, 'onPassword')
            ),
            'connection' => new InteractiveInputDefinition(
                new InputDefinition(array(new InputArgument('input', InputArgument::IS_ARRAY))),
                array($this, 'onConnection')
            )
        );
    }

    /**
     * @param Console $console
     * @param string $input
     * @return $this
     */
    public function onCancel(Console $console, $input)
    {
        /** @var ConsoleInterface $subConsole */
        if($subConsole = $this->getEnvironmentData($console, 'subconsole')){
            $console->write('Connection closed', 'description');
        }
        $this->baseOnCancel($console, $input);
        return $this;
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('resource', InputArgument::REQUIRED, 'Resource to connect'),
            new InputOption('port', 'p', InputOption::VALUE_REQUIRED, 'Port to connect', 23)
        ));
    }
}