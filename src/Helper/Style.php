<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Style extends AbstractComponent
{

    private string $style;

    public function __construct(string $style)
    {
        $this->style = $style;
    }

    public function show(): string
    {
        return '<style>' . $this->style . '</style>';
    }
}