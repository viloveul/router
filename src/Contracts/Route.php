<?php

namespace Viloveul\Router\Contracts;

interface Route
{
    /**
     * @param string $method
     */
    public function addMethod(string $method): self;

    /**
     * @param string $tag
     */
    public function addTag(string $tag): self;

    public function getHandler();

    public function getMethods(): array;

    public function getName(): string;

    public function getParams(): array;

    public function getPattern(): string;

    public function getTags(): array;

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
