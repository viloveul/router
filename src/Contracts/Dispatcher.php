<?php

namespace Viloveul\Router\Contracts;

interface Dispatcher
{
    /**
     * @param $method
     * @param $request
     */
    public function dispatch($method, $request);

    public function getBase();

    /**
     * @param $base
     */
    public function setBase($base);
}
