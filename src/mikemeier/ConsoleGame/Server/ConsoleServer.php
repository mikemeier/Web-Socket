<?php

namespace mikemeier\ConsoleGame\Server;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Console\Environment;
use mikemeier\ConsoleGame\Server\Client\Client;
use mikemeier\ConsoleGame\Server\Client\ClientPool;
use mikemeier\ConsoleGame\Server\Message\Message;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use mikemeier\ConsoleGame\Output\Line\Decorator\DecoratorInterface;
use mikemeier\ConsoleGame\Command\CommandInterface;

class ConsoleServer implements MessageComponentInterface
{
    /**
     * @var ClientPool
     */
    protected $pool;

    /**
     * @var CommandInterface[]
     */
    protected $commands;

    /**
     * @var DecoratorInterface[]
     */
    protected $decorators;

    /**
     * @param ClientPool $pool
     * @param CommandInterface[] $commands
     * @param DecoratorInterface[] $decorators
     */
    public function __construct(ClientPool $pool, array $commands, array $decorators)
    {
        $this->pool = $pool;
        $this->commands = $commands;
        $this->decorators = $decorators;
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onOpen(ConnectionInterface $connection)
    {
        $client = new Client($connection, new Environment());

        $console = new Console(
            $client,
            $this->commands,
            $this->decorators
        );

        $this->pool->addClient($client);

        $console->write('Welcome', 'welcome', true);

        $this->broadcastUserNumber();
    }

    /**
     * @param ConnectionInterface $connection
     */
    public function onClose(ConnectionInterface $connection)
    {
        $this->pool->removeClient($this->getClient($connection));
        $this->broadcastUserNumber();
    }

    /**
     * @param ConnectionInterface $connection
     * @param \Exception $exception
     */
    public function onError(ConnectionInterface $connection, \Exception $exception)
    {
        var_dump($exception->getMessage());
        $connection->close();
    }

    /**
     * @param ConnectionInterface $connection
     * @param string $message
     */
    public function onMessage(ConnectionInterface $connection, $message)
    {
        if(!$data = json_decode($message, true)){
            return;
        }

        if(!isset($data['event']) || !isset($data['arguments']) || !is_array($data['arguments'])){
            return;
        }

        if(
            $data['event'] == "command" &&
            isset($data['arguments'][0]) &&
            $client = $this->pool->getClient($connection)
        ){
            $client->getConsole()->process($data['arguments'][0]);
        }
    }

    /**
     * @param ConnectionInterface $connection
     * @return Client
     */
    protected function getClient(ConnectionInterface $connection)
    {
        return $this->pool->getClient($connection);
    }

    protected function broadcastUserNumber()
    {
        $this->pool->send(new Message('usernumber', array($this->pool->getClients()->count())));
    }
}