<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Tag extends AbstractComponent
{

    private string $text;

    public function __construct(string $tagName, ?string $value, array|string $properties = [])
    {
        $this->text = Html::tag($tagName, $value, $properties);
    }

    public function show(): string
    {
        return $this->text;
    }

}