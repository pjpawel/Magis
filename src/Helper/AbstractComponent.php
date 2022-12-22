<?php

namespace pjpawel\Magis\Helper;

abstract class AbstractComponent implements ComponentInterface
{

    abstract public function show(): string;

    /**
     * @param array<string,string>|string ...$args
     * @return string
     * @throws \ReflectionException
     */
    public static function createAndShow(array|string ...$args): string
    {
        //$args = func_get_args();
        $component = new \ReflectionClass(static::class);
        $componentObject = $component->newInstanceArgs($args);
        return $component->getMethod('show')->invoke($componentObject);
    }
}