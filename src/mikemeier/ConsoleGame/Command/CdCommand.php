<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Command\Helper\Traits\DirectoryRepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\EnvironmentHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\RepositoryHelperTrait;
use mikemeier\ConsoleGame\Command\Helper\Traits\UserHelperTrait;
use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class CdCommand extends AbstractUserCommand implements AutocompletableCommandInterface
{
    use UserHelperTrait;
    use DirectoryRepositoryHelperTrait;
    use EnvironmentHelperTrait;
    use RepositoryHelperTrait;

    /**
     * @param InputInterface $input
     * @param Console $console
     * @return AutocompletableCommandInterface
     */
    public function execute(InputInterface $input, Console $console)
    {
        if($directoryName = $input->getArgument('directory')){
            if(substr($directoryName, 0, 1) == '/'){
                $this->changeAbsolute($console, $directoryName);
                return $this;
            }
            $this->changeRelative($console, $directoryName);
            return $this;
        }

        $username = $this->getUserHelper()->getUser($console)->getUsername();
        $homeDirectory = $this->getDirectoryRepository()->getHomeDirectory($username);

        $this->setCwd($console, $homeDirectory);
    }

    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    protected function setCwd(Console $console, Directory $directory = null)
    {
        $this->getEnvironmentHelper()->setCwd($console, $directory);
        $console->writeEmptyDecoratedLine();
        return $this;
    }

    /**
     * @param Console $console
     * @param string $path
     */
    protected function changeAbsolute(Console $console, $path)
    {
        $this->change($console, $this->getDirectoryRepository()->getRootDirectory(), $path);
    }

    /**
     * @param Console $console
     * @param $path
     */
    protected function changeRelative(Console $console, $path)
    {
        $this->change($console, $this->getEnvironmentHelper()->getCwd($console), $path);
    }

    /**
     * @param Console $console
     * @param Directory $start
     * @param string $path
     */
    protected function change(Console $console, Directory $start, $path)
    {
        if(!$directory = $this->getDirectoryRepository()->findDirectory($start, $path)){
            $console->write('Invalid directory "'. $path .'"', 'error');
            return;
        }
        $this->setCwd($console, $directory);
    }

    /**
     * @return InputDefinition
     */
    public function getInputDefinition()
    {
        return new InputDefinition(array(
            new InputArgument('directory')
        ));
    }

    /**
     * @param string $input
     * @param Console $console
     * @return bool|string
     */
    public function autocomplete($input, Console $console)
    {
        $cwd = $this->getEnvironmentHelper()->getCwd($console);

        if($input){
            $matches = array();
            foreach($this->getDirectoryRepository()->findDirectory($cwd, $input, true)->getChildren() as $child){
                $name = $child->getName();
                if(strtolower(substr($name, 0, strlen($input))) == strtolower($input)){
                    $matches[] = $child;
                }
            }
        }else{
            $matches = $cwd->getChildren();
        }

        $count = count($matches);
        if($count == 1){
            return $matches[0]->getName();
        }

        if($count > 1){
            $console->writeEmptyDecoratedLine();
            foreach($matches as $directory){
                $line = new Line();
                $line->add(' d ');
                $line->add($directory->getName(), 'directory');
                $console->writeLine($line);
            }
        }

        return false;
    }
}