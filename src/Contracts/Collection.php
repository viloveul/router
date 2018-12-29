<?php

namespace Viloveul\Router\Contracts;

use Viloveul\Router\Contracts\Route;

interface Collection
{
    /**
     * @param $name
     * @param Route        $route
     * @param $overwrite
     */
    public function add($name, Route $route, $overwrite = false): Route;

    public function all(): array;

    /**
     * @param $name
     */
    public function exists($name): bool;

    /**
     * @param $name
     */
    public function get($name): Route;

    /**
     * @param self         $collection
     * @param $overwrite
     */
    public function merge(self $collection, $overwrite = false): self;
}
