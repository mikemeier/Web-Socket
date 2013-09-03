<?php

namespace mikemeier\ConsoleGame\Repository;

use Doctrine\ORM\EntityRepository;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\User\User;

class DirectoryRepository extends EntityRepository
{
    /**
     * @param string $username
     * @return Directory
     */
    public function getHomeDirectory($username)
    {
        $usersDirectory = $this->getUsersDirectory();
        $qb = $this->createQueryBuilder('d');

        $qb
            ->where('d.parent = :usersdirectory')
            ->andWhere('d.name = :username')
            ->setParameter('usersdirectory', $usersDirectory)
            ->setParameter('username', $username)
        ;

        if($directory = $qb->getQuery()->getOneOrNullResult()){
            return $directory;
        }

        $directory = new Directory();

        $this->saveDirectory(
            $directory
                ->setParent($usersDirectory)
                ->setName($username)
        );

        return $directory;
    }

    /**
     * @param string $path
     * @return array
     */
    public function getDirectoryNames($path)
    {
        return array_filter(explode("/", $path));
    }

    /**
     * @param Directory $start
     * @param string $path
     * @param bool $returnLastValid
     * @return Directory
     */
    public function findDirectory(Directory $start, $path, $returnLastValid = false)
    {
        $child = $lastValid = $start;
        foreach($this->getDirectoryNames($path) as $name){
            if($name == '..'){
                $child = $lastValid = $child->getParent();
                continue;
            }
            if(!$child || !$child = $child->getChild($name)){
                return $returnLastValid ? $lastValid : null;
            }
            $lastValid = $child;
        }
        return $child;
    }

    /**
     * @param Directory $parent
     * @param string $name
     * @return $this
     */
    public function createDirectory(Directory $parent, $name)
    {
        $directory = new Directory();
        $this->saveDirectory($directory->setParent($parent)->setName($name));
        return $this;
    }

    /**
     * @return Directory
     */
    public function getUsersDirectory()
    {
        if($directory = $this->findOneBy(array('alias' => Directory::ALIAS_USERS))){
            return $directory;
        }

        $directory = new Directory();

        $this->saveDirectory(
            $directory
                ->setParent($this->getRootDirectory())
                ->setName('Users')
                ->setAlias(Directory::ALIAS_USERS)
        );

        return $directory;
    }

    /**
     * @return Directory
     */
    public function getRootDirectory()
    {
        if($directory = $this->findOneBy(array('root' => true))){
            return $directory;
        }

        $directory = new Directory();
        $this->saveDirectory($directory->setRoot(true));

        return $directory;
    }

    /**
     * @param Directory $directory
     * @return Directory
     */
    protected function saveDirectory(Directory $directory)
    {
        $em = $this->getEntityManager();
        $em->persist($directory);
        $em->flush();
        return $directory;
    }
}