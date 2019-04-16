<?php

namespace Viloveul\Router\Contracts;

use Countable;
use Viloveul\Router\Contracts\Route;

interface Collection extends Countable
{
    /**
     * @param Route $route
     */
    public function add(Route $route): Route;

    public function all(): array;

    /**
     * @param self $collection
     */
    public function merge(self $collection): self;
}
