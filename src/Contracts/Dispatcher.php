<?php

namespace Viloveul\Router\Contracts;

interface Dispatcher
{
    /**
     * @param $method
     * @param $request
     */
    public function watch($method, $request);
}
