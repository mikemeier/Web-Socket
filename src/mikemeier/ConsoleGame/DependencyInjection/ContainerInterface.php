<?php

namespace mikemeier\ConsoleGame\DependencyInjection;

interface ContainerInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function get($id);

    /**
     * @param string $id
     * @param object $object
     * @return ContainerInterface
     */
    public function set($id, $object);
}