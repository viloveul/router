<?php

namespace Viloveul\Router\Contracts;

use Psr\Http\Message\UriInterface;
use Viloveul\Router\Contracts\Route;

interface Dispatcher
{
    /**
     * @param string       $method
     * @param UriInterface $uri
     * @param bool         $throw
     */
    public function dispatch(string $method, UriInterface $uri, bool $throw);

    public function getBase(): string;

    public function routed(): Route;

    /**
     * @param $base
     */
    public function setBase($base): void;
}
