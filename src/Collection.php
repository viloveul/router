<?php

namespace Viloveul\Router;

use Viloveul\Router\Contracts\Collection as ICollection;

class Collection implements ICollection
{
    /**
     * @var string
     */
    protected $base = '/';

    /**
     * @var array
     */
    protected $collections = [];

    /**
     * @param  $method
     * @param  $pattern
     * @param  $handler
     * @return mixed
     */
    public function add($method, $pattern, $handler): ICollection
    {
        $this->collections[] = [
            'methods' => is_scalar($method) ? [strtolower($method)] : array_map('strtolower', (array) $method),
            'pattern' => $this->wrap($pattern),
            'handler' => $handler,
        ];
        return $this;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->collections;
    }

    public function current()
    {
        return current($this->collections);
    }

    /**
     * @return mixed
     */
    public function getBase()
    {
        return $this->base;
    }

    public function key()
    {
        return key($this->collections);
    }

    public function next()
    {
        next($this->collections);
    }

    public function rewind()
    {
        reset($this->collections);
    }

    /**
     * @param $base
     */
    public function setBase($base)
    {
        $this->base = $base;
    }

    public function valid(): bool
    {
        return null !== $this->key();
    }

    /**
     * @param $pattern
     */
    protected function wrap($pattern)
    {
        return '/' . trim($this->getBase() . '/' . trim($pattern, '/'), '/');
    }
}
