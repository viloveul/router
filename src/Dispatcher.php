<?php

namespace Viloveul\Router;

use Viloveul\Router\Collection;
use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Dispatcher as IDispatcher;
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
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    /**
     * @param $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    /**
     * @param $method
     * @param $request
     */
    public function watch($method, $request)
    {
        foreach ($this->collection->all() as $route) {
            $methods = $route->getMethods();
            $pattern = $this->wrap($route->getPattern());
            if (in_array($method, $methods) || in_array('any', $methods)) {
                if (preg_match("#^{$pattern}$#i", $request, $matches)) {
                    return [
                        'route' => $route,
                        'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
                    ];
                }
            }
        }
        throw new NotFoundException("Handler not found.");
    }

    /**
     * @param $pattern
     */
    protected function wrap($pattern)
    {
        return '/' . trim($this->getBase() . '/' . trim($pattern, '/'), '/');
    }
}
