<?php

namespace mikemeier\ConsoleGame\Command\Helper\Traits;

use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;

trait DirectoryRepositoryHelperTrait
{
    use RepositoryHelperTrait;

    /**
     * @return DirectoryRepository
     */
    public function getDirectoryRepository(){
        return $this->getRepositoryHelper()->getRepository(new Directory());
    }
}
