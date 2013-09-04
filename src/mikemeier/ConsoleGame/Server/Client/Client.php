<?php

namespace mikemeier\ConsoleGame\Server\Client;

use mikemeier\ConsoleGame\Console\Environment;
use mikemeier\ConsoleGame\Network\ResourceInterface;
use mikemeier\ConsoleGame\Server\Message\Message;
use Ratchet\ConnectionInterface;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;

class Client implements ResourceInterface
{
    /**
     * @var ConnectionInterface
     */
    protected $connection;

    /**
     * @var Environment
     */
    protected $environment;

    /**
     * @var Console
     */
    protected $console;

    /**
     * @var User
     */
    protected $user;

    /**
     * @param ConnectionInterface $connection
     * @param Environment $environment
     */
    public function __construct(ConnectionInterface $connection, Environment $environment)
    {
        $this->connection = $connection;
        $this->environment = $environment;
    }

    /**
     * @param Message $message
     * @return Client
     */
    public function send(Message $message)
    {
        $this->connection->send(json_encode($message));
        return $this;
    }

    /**
     * @return ConnectionInterface
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @return Environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Client
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Console
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * @param Console $console
     * @return Client
     */
    public function setConsole(Console $console = null)
    {
        $this->console = $console;
        return $this;
    }

    /**
     * @return string
     */
    public function getResourceName()
    {
        if($user = $this->getUser()){
            return (string)$user;
        }
        return null;
    }
}