<?php

namespace Viloveul\Router;

use Viloveul\Router\Contracts\Route as IRoute;

class Route implements IRoute
{
    /**
     * @var mixed
     */
    protected $handler;

    /**
     * @var array
     */
    protected $methods = [];

    /**
     * @var mixed
     */
    protected $pattern;

    /**
     * @param $name
     * @param $pattern
     * @param $params
     */
    public function __construct($pattern, $params)
    {
        extract($this->normalizeParams($params), EXTR_SKIP);

        $this->handler = $handler;
        $this->addMethod($method);

        if (strpos($pattern, '{:') !== false) {
            $this->pattern = preg_replace('~{:(\w+)}~', '(?<\1>[^/]+)', $pattern);
        } else {
            $this->pattern = $pattern;
        }
    }

    /**
     * @param $method
     */
    public function addMethod($method)
    {
        $methods = preg_split('/\|/', $method, -1, PREG_SPLIT_NO_EMPTY);
        $this->methods = array_merge($methods, $this->methods);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @return mixed
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $args
     */
    protected function normalizeParams($args)
    {
        if (is_array($args) && !is_callable($args)) {
            $params = array_merge([
                'handler' => null,
                'method' => 'any',
            ], $args);
        } else {
            $params = [
                'handler' => $args,
                'method' => 'get|post',
            ];
        }
        return $params;
    }
}
