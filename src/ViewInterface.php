<?php

namespace pjpawel\Magis;

interface ViewInterface
{
    public function render(string $template, array $params): string;
}