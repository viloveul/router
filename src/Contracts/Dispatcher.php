<?php

namespace Viloveul\Router\Contracts;

interface Dispatcher
{
    /**
     * @param $method
     * @param $request
     */
    public function dispatch($method, $request);

    public function getBase(): string;

    /**
     * @param $base
     */
    public function setBase($base): void;
}
