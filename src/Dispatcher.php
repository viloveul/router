<?php

namespace Viloveul\Router;

use Viloveul\Router\Collection;
use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Dispatcher as IDispatcher;
use Viloveul\Router\NotFoundException;

class Dispatcher implements IDispatcher
{
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
     * @param $method
     * @param $request
     */
    public function watch($method, $request)
    {
        foreach ($this->collection as $route) {
            if (in_array($method, $route['methods']) || in_array('any', $route['methods'])) {
                if (preg_match("#^{$route['pattern']}$#i", $request, $matches)) {
                    return [
                        'handler' => $route['handler'],
                        'params' => array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY),
                    ];
                }
            }
        }
        throw new NotFoundException("Handler not found.");
    }
}
