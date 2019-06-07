<?php

namespace Viloveul\Router;

use Viloveul\Router\Collection;
use Viloveul\Router\NotFoundException;
use Psr\Http\Message\UriInterface as IUri;
use Viloveul\Router\Contracts\Route as IRoute;
use Viloveul\Router\Contracts\Collection as ICollection;
use Viloveul\Router\Contracts\Dispatcher as IDispatcher;

class Dispatcher implements IDispatcher
{
    /**
     * @var string
     */
    protected $base = '';

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
     * @param IUri   $uri
     * @param bool   $throw
     */
    public function dispatch(string $method, IUri $uri, bool $throw = true)
    {
        $method = strtolower($method);
        $path = $this->extractPathRequestUri($uri);
        foreach ($this->collection->all() as $route) {
            $methods = $route->getMethods();
            $pattern = '/' . trim($route->getPattern(), '/');
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
     * @param string $base
     */
    public function setBase(string $base): void
    {
        $this->base = trim(parse_url($base, PHP_URL_PATH), '/');
    }

    /**
     * @param IUri $uri
     */
    protected function extractPathRequestUri(IUri $uri): string
    {
        $path = trim($uri->getPath(), '/');
        if (!$this->getBase()) {
            return '/' . $path;
        } elseif (strpos($path, $this->getBase() . '/') === 0 || $path === $this->getBase()) {
            return '/' . trim(substr($path, strlen($this->getBase())), '/');
        } else {
            return '';
        }
    }
}
