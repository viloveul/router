<?php

namespace Viloveul\Router;

use Viloveul\Router\Collection;
use Viloveul\Router\NotFoundException;
use Viloveul\Router\Contracts\Route as IRoute;
use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Dispatcher as IDispatcher;

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
        if (null === $collection) {
            $collection = new Collection();
        }
        $this->collection = $collection;
    }

    /**
     * @param string $method
     * @param string $request
     * @param bool   $throw
     */
    public function dispatch(string $method, string $request, bool $throw = true)
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
                    return true;
                }
            }
        }
        if ($throw !== false) {
            throw new NotFoundException("Handler not found.");
        }
        return false;
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
