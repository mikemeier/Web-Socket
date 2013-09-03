<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use mikemeier\ConsoleGame\Repository\DirectoryRepository;
use mikemeier\ConsoleGame\Command\Helper\UserHelper;

class CdCommand extends AbstractUserCommand implements AutocompletableCommandInterface
{
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

        /** @var UserHelper $userHelper */
        $userHelper = $this->getHelper('user');

        $username = $userHelper->getUser($console)->getUsername();
        $homeDirectory = $this->getDirectoryRepository()->getHomeDirectory($username);

        $this->setCwd($console, $homeDirectory);
    }

    /**
     * @return DirectoryRepository
     */
    protected function getDirectoryRepository()
    {
        return $this->getHelper('repository')->getRepository(new Directory());
    }

    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    protected function setCwd(Console $console, Directory $directory = null)
    {
        $this->getHelper('environment')->setCwd($console, $directory);
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
        $this->change($console, $this->getHelper('environment')->getCwd($console), $path);
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
        $cwd = $this->getHelper('environment')->getCwd($console);

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
            return $this->getName().' '.$matches[0]->getName();
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