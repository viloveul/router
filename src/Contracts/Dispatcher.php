<?php

namespace Viloveul\Router\Contracts;

use Viloveul\Router\Contracts\Route;

interface Dispatcher
{
    /**
     * @param string $method
     * @param string $request
     * @param bool   $throw
     */
    public function dispatch(string $method, string $request, bool $throw);

    public function getBase(): string;

    public function routed(): Route;

    /**
     * @param $base
     */
    public function setBase($base): void;
}
