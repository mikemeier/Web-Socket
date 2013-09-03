<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Doctrine\ORM\EntityRepository;
use mikemeier\ConsoleGame\Command\Helper\UserHelper;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;

class LoginCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return CommandInterface
     */
    public function execute(InputInterface $input, Console $console)
    {
        $username = strtolower($input->getArgument('username'));
        $password = $input->getArgument('password');

        /** @var EntityRepository $userRepo */
        $userRepo = $this->getHelper('repository')->getRepository(new User());

        /** @var User $user */
        if(!$user = $userRepo->findOneBy(array('username' => $username, 'password' => $password))){
            $console->write('Username and/or password wrong', 'error');
            return $this;
        }

        /** @var UserHelper $userHelper */
        $userHelper = $this->getHelper('user');

        /** @var DirectoryRepository $directoryRepo */
        $directoryRepo = $this->getHelper('repository')->getRepository(new Directory());

        $userHelper->loginUser(
            $console,
            $user,
            $directoryRepo->getHomeDirectory($userHelper->getUser($console)->getUsername()),
            $this->getHelper('environment')
        );

        $console->write('Hi '. $user->getUsername(), 'welcome');

        return $this;
    }

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null)
    {
        return $this->getHelper('feedback')->prepareFeedback($input, array(
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