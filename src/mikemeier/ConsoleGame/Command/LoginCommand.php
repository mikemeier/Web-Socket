<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\DirectoryRepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\FeedbackHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Command\Interactive\InteractiveInputDefinition;
use mikemeier\ConsoleGame\Command\Traits\InteractiveCommandTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\Exception\OutOfIpsException;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class LoginCommand extends AbstractCommand implements InteractiveCommandInterface
{
    use UserHelperTrait;
    use RepositoryHelperTrait;
    use DirectoryRepositoryHelperTrait;
    use FeedbackHelperTrait;
    use RouterHelperTrait;
    use InteractiveCommandTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface
     */
    public function execute(InputInterface $input, Console $console)
    {
        if($this->getUserHelper()->hasUser($console)){
            $console->write('Already loggedin', 'error');
            return $this;
        }

        $console->write('Username:', 'question');
        $this->activateInteractiveInputDefinition($console, 'username');

        return $this;
    }

    /**
     * @param Console $console
     * @param InputInterface $input
     * @return string
     */
    public function onUsername(Console $console, InputInterface $input)
    {
        $username = $input->getArgument('username');
        $this->setEnvironmentData($console, 'username', $username);
        $console->write($username);
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

        if(!$user = $this->getUser($username, $input->getArgument('password'))){
            $console->write('Username and/or password wrong', 'error');
            $console->write('Username:', 'question');
            return 'username';
        }

        try {
            $this->getUserHelper()->loginUser(
                $console,
                $user,
                $this->getDirectoryRepository(),
                $this->getEnvironmentHelper()->getEnvironment($console),
                $this->getRouterHelper()->getRouter()
            );
        }catch(OutOfIpsException $e){
            $console->write('No more IPs from DHCP - Could not login', 'error');
        }

        $console->write('Loggedin as '. $username, 'success');
        $console->writeEmptyDecoratedLine();
        $this->stop($console);
    }

    /**
     * @return InteractiveInputDefinition[]
     */
    protected function getInteractiveInputDefinitions()
    {
        return array(
            'username' => new InteractiveInputDefinition(
                new InputDefinition(array(new InputArgument('username'))),
                array($this, 'onUsername')
            ),
            'password' => new InteractiveInputDefinition(
                new InputDefinition(array(new InputArgument('password'))),
                array($this, 'onPassword')
            )
        );
    }

    /**
     * @param string $username
     * @param string $password
     * @return User
     */
    protected function getUser($username, $password)
    {
        $userRepo = $this->getRepositoryHelper()->getRepository(new User());
        return $userRepo->findOneBy(array('username' => $username, 'password' => $password));
    }
}