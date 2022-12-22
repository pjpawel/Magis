<?php

namespace pjpawel\Magis\Helper;

abstract class AbstractComponent implements ComponentInterface
{

    abstract public function show(): string;

    /**
     * @param mixed ...$args
     * @return string
     * @throws \ReflectionException
     */
    public static function createAndShow(mixed ...$args): string
    {
        /** @var array<int|mixed> $args */
        $component = new \ReflectionClass(static::class);
        $componentObject = $component->newInstanceArgs($args);
        /** @var static $componentObject */
        return $componentObject->show();
    }
}