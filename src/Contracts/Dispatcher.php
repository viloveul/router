<?php

namespace Viloveul\Router\Contracts;

interface Dispatcher
{
    public function getBase();

    /**
     * @param $base
     */
    public function setBase($base);

    /**
     * @param $method
     * @param $request
     */
    public function watch($method, $request);
}
