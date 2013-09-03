<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\DirectoryRepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\FeedbackHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RouterHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Network\OutOfIpsException;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class LoginCommand extends AbstractCommand
{
    use UserHelperTrait;
    use RepositoryHelperTrait;
    use DirectoryRepositoryHelperTrait;
    use FeedbackHelperTrait;
    use EnvironmentHelperTrait;
    use RouterHelperTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface
     */
    public function execute(InputInterface $input, Console $console)
    {
        $username = strtolower($input->getArgument('username'));
        $password = $input->getArgument('password');

        $userRepo = $this->getRepositoryHelper()->getRepository(new User());

        /** @var User $user */
        if(!$user = $userRepo->findOneBy(array('username' => $username, 'password' => $password))){
            $console->write('Username and/or password wrong', 'error');
            return $this;
        }

        $environment = $this->getEnvironmentHelper()->getEnvironment($console);

        try {
            $this->getUserHelper()->loginUser(
                $console,
                $user,
                $this->getDirectoryRepository(),
                $environment,
                $this->getRouterHelper()->getRouter()
            );
            $console->write('Welcome back '. $user->getUsername(), 'welcome');
        }catch(OutOfIpsException $e){
            $console->write('No more IPs from DHCP... Could not login', 'error');
        }

        return $this;
    }

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null)
    {
        return $this->getFeedbackHelper()->prepareFeedback($this, $input, array(
            'password' => str_repeat('*', strlen($input->getArgument('password')))
        ));
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('username', InputArgument::REQUIRED, 'Username for login'),
            new InputArgument('password', InputArgument::REQUIRED, 'Password for login')
        ));
    }
}