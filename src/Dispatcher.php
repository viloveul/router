<?php

namespace Viloveul\Router;

use Viloveul\Router\Collection;
use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Dispatcher as IDispatcher;
use Viloveul\Router\Contracts\Route as IRoute;
use Viloveul\Router\NotFoundException;

class Dispatcher implements IDispatcher
{
    /**
     * @var string
     */
    protected $base = '/';

    /**
     * @var mixed
     */
    protected $collection = null;

    /**
     * @var mixed
     */
    protected $route;

    /**
     * @param ICollection $collection
     */
    public function __construct(ICollection $collection = null)
    {
        if (is_null($collection)) {
            $collection = new Collection;
        }
        $this->collection = $collection;
    }

    /**
     * @param $method
     * @param $request
     */
    public function dispatch($method, $request)
    {
        $method = strtolower($method);
        $path = '/' . trim($request, '/');
        foreach ($this->collection->all() as $route) {
            $methods = $route->getMethods();
            $pattern = $this->wrap(trim($route->getPattern(), '/'));
            if (in_array($method, $methods) || in_array('any', $methods)) {
                if (preg_match("#^{$pattern}$#i", $path, $matches) && $route->getHandler() !== null) {
                    $route->setParams(array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
                    $this->route = $route;
                    return $this;
                }
            }
        }
        throw new NotFoundException("Handler not found.");
    }

    /**
     * @return mixed
     */
    public function getBase(): string
    {
        return $this->base;
    }

    /**
     * @return mixed
     */
    public function routed(): IRoute
    {
        return $this->route;
    }

    /**
     * @param $base
     */
    public function setBase($base): void
    {
        $this->base = $base;
    }

    /**
     * @param $pattern
     */
    protected function wrap($pattern)
    {
        return '/' . trim($this->getBase() . '/' . $pattern, '/');
    }
}
