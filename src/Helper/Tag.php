<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Tag extends AbstractComponent
{

    private string $text;

    public function __construct(string $tagName, array|string $properties = [])
    {
        if (is_array($properties)) {
            $propertiesTransformed = [];
            foreach ($properties as $key => $value) {
                $propertiesTransformed[] = $key . '="' . $value . '"';
            }
            $properties = implode(' ', $propertiesTransformed);
        }
        $this->text = '<' . $tagName . ' ' . $properties . '>';
    }

    public function show(): string
    {
        return $this->text;
    }

}