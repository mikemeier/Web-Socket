<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class LoginCommand extends AbstractCommand
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

        /** @var User $user */
        if(!$user = $userRepo->findOneBy(array('username' => $username, 'password' => $password))){
            $console->write('Username and/or password wrong', 'error');
            return;
        }

        $this->loginUser($console, $user);
        $console->write('Hi '. $user->getUsername());
    }

    /**
     * @param InputInterface $input
     * @param string $default
     * @return string
     */
    public function getFeedback(InputInterface $input, $default = null)
    {
        return $this->getName().' '. $input->getArgument('username') .' ****';
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('username', InputArgument::REQUIRED),
            new InputArgument('password', InputArgument::REQUIRED)
        ));
    }
}