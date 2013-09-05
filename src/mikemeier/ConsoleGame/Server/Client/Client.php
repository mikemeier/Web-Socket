<?php

namespace mikemeier\ConsoleGame\Server\Client;

use mikemeier\ConsoleGame\Console\Environment;
use mikemeier\ConsoleGame\Network\Message\NetsendMessage;
use mikemeier\ConsoleGame\Network\Resource\DnsResourceInterface;
use mikemeier\ConsoleGame\Network\Resource\ReceiveNetsendMessageInterface;
use mikemeier\ConsoleGame\Server\Message\Message;
use Ratchet\ConnectionInterface;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Console\Console;

class Client implements DnsResourceInterface, ReceiveNetsendMessageInterface
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

    /**
     * @return string
     */
    public function isOnline()
    {
        try {
            $this->connection->send('ping');
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     * @param NetsendMessage $message
     * @return bool
     */
    public function receiveNetsendMessage(NetsendMessage $message)
    {
        try {
            $this->getConsole()
                ->write('NetsendMessage from '. $message->getSender(), array('netsend', 'sender'))
                ->write($message->getText(), array('netsend', 'message'))
            ;
            return true;
        }catch(\Exception $e){
            return false;
        }
    }
}