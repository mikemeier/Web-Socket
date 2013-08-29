<?php

namespace mikemeier\ConsoleGame\Server\Client;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use mikemeier\ConsoleGame\Server\Message\Message;
use Ratchet\ConnectionInterface;

class ClientPool
{
    /**
     * @var Collection|Client[]
     */
    protected $clients;

    public function __construct()
    {
        $this->clients = new ArrayCollection();
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param ConnectionInterface $connection
     * @return Client
     */
    public function getClient(ConnectionInterface $connection)
    {
        foreach($this->getClients() as $client){
            if($client->getConnection() == $connection){
                return $client;
            }
        }
        return null;
    }

    /**
     * @param Client $client
     * @return ClientPool
     */
    public function addClient(Client $client)
    {
        $this->clients->add($client);
        return $this;
    }

    /**
     * @param Client $client
     * @return ClientPool
     */
    public function removeClient(Client $client)
    {
        $this->clients->removeElement($client);
        return $this;
    }

    /**
     * @param Message $message
     * @return $this
     */
    public function send(Message $message)
    {
        foreach($this->getClients() as $client){
            $client->send($message);
        }
        return $this;
    }
}