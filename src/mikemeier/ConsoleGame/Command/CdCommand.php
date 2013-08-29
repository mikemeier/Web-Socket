<?php

namespace mikemeier\ConsoleGame\Command;

use mikemeier\ConsoleGame\Console\Console;
use mikemeier\ConsoleGame\Filesystem\Directory;
use mikemeier\ConsoleGame\Output\Line\Line;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;

class CdCommand extends AbstractUserCommand implements AutocompletableCommandInterface
{
    /**
     * @param InputInterface $input
     * @param Console $console
     * @return void
     */
    public function execute(InputInterface $input, Console $console)
    {
        if($directoryName = $input->getArgument('directory')){
            if(substr($directoryName, 0, 1) == '/'){
                $this->changeAbsolute($console, $directoryName);
                return;
            }
            if(substr($directoryName, 0, 2) == '..'){
                $this->changeParent($console);
                return;
            }
            $this->changeRelative($console, $directoryName);
        }else{
            $this->setCwd($console, $this->getHomeDirectory($this->getUser($console)->getUsername()));
        }
    }

    /**
     * @param Console $console
     * @param Directory $directory
     * @return $this
     */
    protected function setCwd(Console $console, Directory $directory = null)
    {
        parent::setCwd($console, $directory);
        $this->writeEmptyLine($console);
        return $this;
    }

    /**
     * @param Console $console
     * @param string $path
     */
    protected function changeAbsolute(Console $console, $path)
    {
        $this->change($console, $this->getRootDirectory(), $path);
    }

    /**
     * @param Console $console
     * @param $path
     */
    protected function changeRelative(Console $console, $path)
    {
        $this->change($console, $this->getCwd($console), $path);
    }

    /**
     * @param Console $console
     * @param Directory $start
     * @param $path
     */
    protected function change(Console $console, Directory $start, $path)
    {
        $child = $start;
        foreach($this->getDirectories($path) as $name){
            if(!$child = $child->getChild($name)){
                $console->write('Invalid directory "'. $path .'"', 'error');
                return;
            }
        }
        $this->setCwd($console, $child);
    }

    /**
     * @param Console $console
     */
    protected function changeParent(Console $console)
    {
        if($parent = $this->getCwd($console)->getParent()){
            $this->setCwd($console, $parent);
        }
    }

    /**
     * @param string $path
     * @return array
     */
    protected function getDirectories($path)
    {
        return array_filter(explode("/", $path));
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
        $cwd = $this->getCwd($console);

        if($input){
            $matches = array();
            foreach($cwd->getChildren() as $child){
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
            $this->writeEmptyLine($console);
            foreach($matches as $directory){
                $line = new Line();
                $line->add('d', 'tab-20');
                $line->add($directory->getName());
                $console->writeLine($line);
            }
        }

        return false;
    }
}