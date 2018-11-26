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

    public function getPattern();
}
