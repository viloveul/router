<?php

namespace Viloveul\Router;

use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Route as IRoute;

class Collection implements ICollection
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @param  IRoute  $route
     * @return mixed
     */
    public function add(IRoute $route): ICollection
    {
        $this->collections[$route->getName()] = $route;
        return $this;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->collections;
    }

    /**
     * @param $name
     */
    public function exists($name)
    {
        return array_key_exists($name, $this->collections);
    }

    /**
     * @param  $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->collections[$name];
    }

    /**
     * @param ICollection $collection
     */
    public function merge(ICollection $collection)
    {
        foreach ($collection->all() as $route) {
            $this->add($route);
        }
    }
}
