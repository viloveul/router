<?php

namespace Viloveul\Router;

use Viloveul\Router\ConflictException;
use Viloveul\Router\Contracts\Route as IRoute;
use Viloveul\Router\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @param $name
     * @param IRoute       $route
     * @param $overwrite
     */
    public function add($name, IRoute $route, $overwrite = false): IRoute
    {
        if ($this->exists($name) && $overwrite === false) {
            throw new ConflictException("Route with name {$name} already exists.");
        }
        $route->setName($name);
        $this->collections[$name] = $route;
        return $route;
    }

    /**
     * @return mixed
     */
    public function all(): array
    {
        return $this->collections;
    }

    /**
     * @param $name
     */
    public function exists($name): bool
    {
        return array_key_exists($name, $this->collections);
    }

    /**
     * @param  $name
     * @return mixed
     */
    public function get($name): IRoute
    {
        return $this->collections[$name];
    }

    /**
     * @param ICollection  $collection
     * @param $overwrite
     */
    public function merge(ICollection $collection, $overwrite = false): ICollection
    {
        foreach ($collection->all() as $name => $route) {
            $this->add($name, $route, $overwrite);
        }
        return $this;
    }
}
