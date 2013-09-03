<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class RegisterCommand extends AbstractCommand
{
    /**
     * @param InputInterface $input
     * @param Console $console
     */
    public function execute(InputInterface $input, Console $console)
    {
        $username = strtolower($input->getArgument('username'));
        $password = $input->getArgument('password');

        $userRepo = $this->getHelper('repository')->getRepository(new User());
        if($user = $userRepo->findOneBy(array('username' => $username))){
            $console->write('Username already exists', 'error');
            return $this;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        $em = $this->getHelper('entitymanager');
        $em->persist($user);
        $em->flush();

        $this->getHelper('repository')->getRepository(new Directory())->getHomeDirectory($username);
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
            new InputArgument('password', InputArgument::REQUIRED, 'Password for login'),
            new InputOption('login', null, null, 'If given, auto-login after successfull registration')
        ));
    }
}