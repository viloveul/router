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
     * @var array
     */
    protected $middlewares = [];

    /**
     * @var mixed
     */
    protected $name = null;

    /**
     * @var array
     */
    protected $params = [];

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
        }
        if (!empty($method)) {
            $this->addMethod($method);
        }
        if (!empty($middleware)) {
            $this->addMiddleware($middleware);
        }
    }

    /**
     * @param $method
     */
    public function addMethod($method): IRoute
    {
        if (is_array($method)) {
            foreach ($method as $v) {
                $this->addMethod($v);
            }
            return $this;
        }
        $methods = preg_split('/\|/', strtolower($method), -1, PREG_SPLIT_NO_EMPTY);
        $this->methods = array_merge($methods, $this->methods);
        return $this;
    }

    /**
     * @param $middleware
     */
    public function addMiddleware($middleware): IRoute
    {
        if (is_array($middleware) && !is_callable($middleware)) {
            foreach ($middleware as $value) {
                $this->addMiddleware($middleware);
            }
            return $this;
        }
        $this->middlewares[] = $middleware;
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
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @return mixed
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    /**
     * @return mixed
     */
    public function getName(): string
    {
        return is_null($this->name) ? '' : $this->name;
    }

    /**
     * @return mixed
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @return mixed
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param $handler
     */
    public function setHandler($handler): void
    {
        $this->handler = $handler;
    }

    /**
     * @param $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = array_replace_recursive($this->params, $params);
    }

    /**
     * @param $pattern
     */
    public function setPattern(string $pattern): void
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
    protected function normalizeParams($args): array
    {
        if (is_array($args) && !is_callable($args)) {
            $params = array_replace_recursive([
                'handler' => null,
                'method' => null,
                'middleware' => null,
            ], $args);
        } else {
            $params = [
                'handler' => $args,
                'method' => null,
                'middleware' => null,
            ];
        }
        return $params;
    }
}
