<?php

namespace Viloveul\Router\Contracts;

interface Route
{
    /**
     * @param $method
     */
    public function addMethod($method);

    public function getHandler();

    public function getMethods();

    public function getName();

    public function getParams(): array;

    public function getPattern();

    /**
     * @param $handler
     */
    public function setHandler($handler);

    /**
     * @param $name
     */
    public function setName($name);

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @param $pattern
     */
    public function setPattern($pattern);
}
