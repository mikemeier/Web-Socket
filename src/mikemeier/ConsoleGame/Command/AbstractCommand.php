<?php

namespace mikemeier\ConsoleGame\Command;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use mikemeier\ConsoleGame\Command\Helper\HelperInterface;
use mikemeier\ConsoleGame\Command\Helper\Traits\HelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\DependencyInjection\ContainerInterface;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;
use mikemeier\ConsoleGame\Server\Message\Message;
use mikemeier\ConsoleGame\User\User;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractCommand implements CommandInterface
{
    use HelperTrait;

    /**
     * @param array $helpers
     */
    public function __construct(array $helpers = array())
    {
        foreach($helpers as $helper){
            $this->addHelper($helper);
        }
    }

    /**
     * @param Console $console
     * @return bool
     */
    public function isAvailable(Console $console)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return null;
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
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
    }
}