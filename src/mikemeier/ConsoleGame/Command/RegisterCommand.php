<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\DirectoryRepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\EntityManagerHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\FeedbackHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RepositoryHelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class RegisterCommand extends AbstractCommand
{
    use DirectoryRepositoryHelperTrait;
    use RepositoryHelperTrait;
    use EntityManagerHelperTrait;
    use FeedbackHelperTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return $this
     */
    public function execute(InputInterface $input, Console $console)
    {
        $username = strtolower($input->getArgument('username'));
        $password = $input->getArgument('password');

        $userRepo = $this->getRepositoryHelper()->getRepository(new User());
        if($user = $userRepo->findOneBy(array('username' => $username))){
            $console->write('Username already exists', 'error');
            return $this;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        $em = $this->getEntityManagerHelper()->getEntityManager();
        $em->persist($user);
        $em->flush();

        $this->getDirectoryRepository()->getHomeDirectory($username);
        $console->write('OK', 'success');

        if($input->getOption('login')){
            $console->processCommand($console->getCommand('login'), new ArrayInput(array(
                'username' => $username,
                'password' => $password
            )));
        }
    }

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null)
    {
        return $this->getFeedbackHelper()->prepareFeedback($this->getInputDefinition(), $input, array(
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
            new InputArgument('password', InputArgument::REQUIRED, 'Password for login'),
            new InputOption('login', null, null, 'If given, auto-login after successfull registration')
        ));
    }
}