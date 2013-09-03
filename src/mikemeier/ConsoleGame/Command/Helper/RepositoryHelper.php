<?php

namespace mikemeier\ConsoleGame\Command\Helper;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class RepositoryHelper extends AbstractHelper
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string|object $entity
     * @return EntityRepository
     */
    public function getRepository($entity)
    {
        if(is_object($entity)){
            $entity = get_class($entity);
        }
        return $this->entityManager->getRepository($entity);
    }
}