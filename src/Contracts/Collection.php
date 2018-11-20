<?php

namespace Viloveul\Router\Contracts;

use Iterator;

interface Collection extends Iterator
{
    /**
     * @param $method
     * @param $pattern
     * @param $handler
     */
    public function add($method, $pattern, $handler): Collection;

    public function all();

    public function getBase();

    /**
     * @param $base
     */
    public function setBase($base);
}
