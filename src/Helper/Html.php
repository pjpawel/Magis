<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Html
{

    public static function a(string $href, array $properties = []): string
    {
        $properties['href'] = $href;
        return self::tag('a', $properties);
    }

    public static function tag(string $name, array $properties = []): string
    {
        $propertiesTransformed = [];
        foreach ($properties as $key => $value) {
            $propertiesTransformed[] = $key . '="' . $value . '"';
        }
        $properties = implode(' ', $propertiesTransformed);
        return '<' . $name . ' ' . $properties . '>';
    }

}