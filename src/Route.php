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
    protected $name;

    /**
     * @var mixed
     */
    protected $pattern;

    /**
     * @param array $params
     */
    public function __construct($name, array $params = [])
    {
        $args = array_merge([
            'handler' => null,
            'methods' => 'any',
            'pattern' => '/',
        ], $params);

        $this->name = $name;
        $this->handler = $args['handler'];
        $this->pattern = $args['pattern'];
        if ($args['methods']) {
            foreach ((array) $args['methods'] as $method) {
                $this->addMethod($method);
            }
        }
    }

    /**
     * @param $method
     */
    public function addMethod($method)
    {
        $this->methods[] = $method;
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
}
