<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;
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

        $userRepo = $this->getRepository(new User());
        if($user = $userRepo->findOneBy(array('username' => $username))){
            $console->write('Username already exists', 'error');
            return;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);

        $em = $this->getEntityManager();
        $em->persist($user);
        $em->flush();

        if($input->getOption('login')){
            $console->processCommand($console->getCommand('login'), new ArrayInput(array(
                'username' => $username,
                'password' => $password
            )));
        }

        $console->write('OK', 'success');
    }

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null)
    {
        return $this->getName().' '. $input->getArgument('username');
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('username', InputArgument::REQUIRED),
            new InputArgument('password', InputArgument::REQUIRED),
            new InputOption('login')
        ));
    }
}