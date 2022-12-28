<?php

namespace pjpawel\Magis\Helper;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class Script extends AbstractComponent
{

    private string $script;

    public function __construct(string $script)
    {
        $this->script = $script;
    }

    public function show(): string
    {
        return '<script>' . $this->script . '</script>';
    }
}