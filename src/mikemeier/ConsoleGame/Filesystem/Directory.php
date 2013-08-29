<?php

namespace mikemeier\ConsoleGame\Filesystem;

use Doctrine\ORM\Mapping as ORM;
use mikemeier\ConsoleGame\User\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="mikemeier\ConsoleGame\Repository\DirectoryRepository")
 * @ORM\Table(name="directory")
 */
class Directory
{
    const
        ALIAS_USERS = 'users'
    ;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var Directory
     * @ORM\ManyToOne(targetEntity="Directory", inversedBy="children")
     */
    protected $parent;

    /**
     * @var Directory[]
     * @ORM\OneToMany(targetEntity="Directory", mappedBy="parent")
     */
    protected $children;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true, unique=true)
     */
    protected $alias;

    /**
     * @var bool
     * @ORM\Column(type="boolean", nullable=true, unique=true)
     */
    protected $root;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="mikemeier\ConsoleGame\User\User", inversedBy="directories")
     */
    protected $user;

    public function __construct()
    {
        $this->children = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getName();
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
    public function getAbsolutePath()
    {
        $directories = $this->getPathDirectories();
        $path = '';
        if(count($directories) == 1){
            $path .= '/';
        }
        return $path.implode("/", $directories);
    }

    /**
     * @return Directory[]
     */
    public function getPathDirectories()
    {
        $directories = array_reverse($this->getParents());
        $directories[] = $this;
        return $directories;
    }

    /**
     * @return Directory[]
     * @throws \LogicException
     */
    public function getParents()
    {
        $parents = array();
        $directory = $this;
        while($parent = $directory->getParent()){
            if($parent == $this OR in_array($parent, $parents)){
                throw new \LogicException("Infinite parent loop");
            }
            $directory = $parents[] = $parent;
        }
        if(!$directory->isRoot()){
            throw new \LogicException("Top directory is not root");
        }
        return $parents;
    }

    /**
     * @return Directory[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param string $name
     * @return Directory
     */
    public function getChild($name)
    {
        foreach($this->getChildren() as $child){
            if($child->getName() == $name){
                return $child;
            }
        }
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Directory
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Directory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Directory $parent
     * @param bool $stopPropagation
     * @return Directory
     */
    public function setParent(Directory $parent = null, $stopPropagation = false)
    {
        if(!$stopPropagation){
            if($this->parent){
                $this->parent->removeChild($this, true);
            }
            if($parent){
                $parent->addChild($this, true);
            }
        }
        $this->parent = $parent;
        return $this;
    }

    /**
     * @param Directory $directory
     * @param bool $stopPropagation
     * @return Directory
     */
    public function removeChild(Directory $directory, $stopPropagation = false)
    {
        if(!$stopPropagation){
            $directory->setParent(null, true);
        }
        $this->children->removeElement($directory);
        return $this;
    }

    /**
     * @param Directory $directory
     * @param bool $stopPropagation
     * @return Directory
     */
    public function addChild(Directory $directory, $stopPropagation = false)
    {
        if(!$stopPropagation){
            $directory->setParent($this, true);
        }
        $this->children->add($directory);
        return $this;
    }

    /**
     * @return boolean
     */
    public function isRoot()
    {
        return $this->root;
    }

    /**
     * @param boolean $root
     * @return Directory
     */
    public function setRoot($root)
    {
        $this->root = $root;
        return $this;
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
     * @param bool $stopPropagation
     * @return Directory
     */
    public function setUser(User $user = null, $stopPropagation = false)
    {
        if(!$stopPropagation){
            if($this->user){
                $this->user->removeDirectory($this, true);
            }
            if($user){
                $user->addDirectory($this, true);
            }
        }
        $this->user = $user;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias
     * @return Directory
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }
}