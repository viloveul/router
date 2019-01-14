<?php

namespace Viloveul\Router\Contracts;

interface Route
{
    /**
     * @param $method
     */
    public function addMethod($method): self;

    /**
     * @param $middleware
     */
    public function addMiddleware($middleware): self;

    public function getHandler();

    public function getMethods(): array;

    public function getMiddlewares(): array;

    public function getName(): string;

    public function getParams(): array;

    public function getPattern(): string;

    /**
     * @param $handler
     */
    public function setHandler($handler): void;

    /**
     * @param $name
     */
    public function setName(string $name): void;

    /**
     * @param array $params
     */
    public function setParams(array $params): void;

    /**
     * @param $pattern
     */
    public function setPattern(string $pattern): void;
}
