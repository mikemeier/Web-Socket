<?php

namespace mikemeier\ConsoleGame\DependencyInjection;

interface ContainerInterface
{
    /**
     * @param string $id
     * @return mixed
     */
    public function get($id);
}