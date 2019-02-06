<?php

namespace Viloveul\Router\Contracts;

use Viloveul\Router\Contracts\Route;

interface Dispatcher
{
    /**
     * @param $method
     * @param $request
     */
    public function dispatch($method, $request);

    public function getBase(): string;

    public function routed(): Route;

    /**
     * @param $base
     */
    public function setBase($base): void;
}
