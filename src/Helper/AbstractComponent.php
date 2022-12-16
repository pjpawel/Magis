<?php

namespace pjpawel\Magis\Helper;

abstract class AbstractComponent implements ComponentInterface
{

    abstract public function show(): string;

    public static function createAndShow(...$args): string
    {
        //$args = func_get_args();
        $component = new \ReflectionClass(static::class);
        $componentObject = $component->newInstanceArgs($args);
        return $component->getMethod('show')->invoke($componentObject);
    }
}