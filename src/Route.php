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
    protected $name = null;

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

        $parts = explode(' ', $pattern, 2);
        $this->setPattern(array_pop($parts));
        $this->setHandler($handler);
        if (isset($parts[0])) {
            $this->addMethod($parts[0]);
        } else {
            $this->addMethod($method);
        }
    }

    /**
     * @param $method
     */
    public function addMethod($method)
    {
        $methods = preg_split('/\|/', strtolower($method), -1, PREG_SPLIT_NO_EMPTY);
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param $pattern
     */
    public function setPattern($pattern)
    {
        if (strpos($pattern, '{:') !== false) {
            $this->pattern = preg_replace('~{:(\w+)}~', '(?<\1>[^/]+)', $pattern);
        } else {
            $this->pattern = $pattern;
        }
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
