<?php

namespace Viloveul\Router;

use Viloveul\Router\Contracts\Route as IRoute;
use Viloveul\Router\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @param IRoute $route
     */
    public function add(IRoute $route): IRoute
    {
        $this->collections[] = $route;
        return $route;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->collections;
    }

    public function count(): int
    {
        return count($this->collections);
    }

    /**
     * @param ICollection $collection
     */
    public function merge(ICollection $collection): ICollection
    {
        foreach ($collection->all() as $route) {
            $this->add($route);
        }
        return $this;
    }
}
