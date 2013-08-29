<?php

namespace mikemeier\ConsoleGame\User;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use mikemeier\ConsoleGame\Filesystem\Directory;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="mikemeier\ConsoleGame\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var Directory[]|Collection
     * @ORM\OneToMany(targetEntity="mikemeier\ConsoleGame\Filesystem\Directory", mappedBy="user")
     */
    protected $directories;

    public function __construct()
    {
        $this->directories = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return Collection|Directory[]
     */
    public function getDirectories()
    {
        return $this->directories;
    }

    /**
     * @param Directory $directory
     * @param bool $stopPropagation
     * @return $this
     */
    public function removeDirectory(Directory $directory, $stopPropagation = false)
    {
        if(!$stopPropagation){
            $directory->setUser(null, true);
        }

        $this->directories->removeElement($directory);
        return $this;
    }

    /**
     * @param Directory $directory
     * @param bool $stopPropagation
     * @return $this
     */
    public function addDirectory(Directory $directory, $stopPropagation = false)
    {
        if(!$stopPropagation){
            $directory->setUser($this, true);
        }

        $this->directories->add($directory);
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}