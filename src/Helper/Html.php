<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Html
{

    public const OPEN_TAG = '<';
    public const END_TAG = '>';
    public const VALUE_OPEN_END_TAG = '</';

    public static function a(string $href, ?string $value = null, array $properties = []): string
    {
        $properties['href'] = $href;
        return self::tag('a', $value, $properties);
    }

    public static function tag(string $name, ?string $value = null, array|string $properties = []): string
    {
        if (is_array($properties)) {
            $propertiesTransformed = [];
            foreach ($properties as $key => $property) {
                $propertiesTransformed[] = $key . '="' . $property . '"';
            }
            $properties = implode(' ', $propertiesTransformed);
        }
        $tag = self::OPEN_TAG . $name . ' ' . $properties . self::END_TAG;
        if ($value !== null) {
            $tag .= $value . self::VALUE_OPEN_END_TAG . $name . self::END_TAG;
        }
        return $tag;
    }

}