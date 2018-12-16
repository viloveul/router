<?php

namespace Viloveul\Router\Contracts;

use Viloveul\Router\Contracts\Route;

interface Collection
{
    /**
     * @param Route $route
     */
    public function add(Route $route): Route;

    public function all();

    /**
     * @param $name
     */
    public function exists($name);

    /**
     * @param $name
     */
    public function get($name);

    /**
     * @param Collection $collection
     */
    public function merge(Collection $collection);
}
