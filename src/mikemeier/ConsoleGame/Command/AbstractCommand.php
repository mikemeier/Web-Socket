<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\DependencyInjection\ContainerInterface;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Server\Message\Message;
use mikemeier\ConsoleGame\User\User;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;
use Symfony\Component\Console\Input\InputDefinition;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param string|object $entity
     * @return EntityRepository
     */
    protected function getRepository($entity)
    {
        if(is_object($entity)){
            $entity = get_class($entity);
        }
        return $this->getEntityManager()->getRepository($entity);
    }

    /**
     * @return Directory
     */
    protected function getRootDirectory()
    {
        return $this->getDirectoryRepository()->getRootDirectory();
    }

    /**
     * @return string
     */
    public function getName()
    {
        $reflection = new \ReflectionClass($this);
        return strtolower(substr($reflection->getShortName(), 0, -7));
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition();
    }

    /**
     * @return ContainerInterface
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return AbstractCommand
     */
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }

    /**
     * @param InputInterface $input
     * @param string $feedback
     * @return string
     */
    public function getFeedback(InputInterface $input, $feedback = null)
    {
        return $feedback;
    }

    /**
     * @return array
     */
    public function getAliases()
    {
        return array();
    }

    /**
     * @param Console $console
     * @param User $user
     * @return $this
     */
    protected function loginUser(Console $console, User $user)
    {
        $console->getClient()
            ->setUser($user)
            ->send(new Message('loggedin', array($user->getUsername())))
        ;
        $this->setCwd($console, $this->getDirectoryRepository()->getHomeDirectory($user->getUsername()));
        return $this;
    }

    /**
     * @param Console $console
     * @return $this
     */
    protected function logoutUser(Console $console)
    {
        $console->getClient()
            ->setUser(null)
            ->send(new Message('loggedout'))
        ;
        $this->setCwd($console, null);
        return $this;
    }

    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    protected function setCwd(Console $console, Directory $directory = null)
    {
        $console->getClient()->getEnvironment()->setCwd($directory);
        return $this;
    }

    /**
     * @param Console $console
     * @return Directory
     */
    protected function getCwd(Console $console)
    {
        return $console->getClient()->getEnvironment()->getCwd();
    }

    /**
     * @return DirectoryRepository
     */
    protected function getDirectoryRepository()
    {
        return $this->getEntityManager()->getRepository(get_class(new Directory()));
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->container->get('em');
    }
}