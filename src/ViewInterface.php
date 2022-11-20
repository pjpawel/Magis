<?php

namespace pjpawel\Magis;

interface ViewInterface
{

    public function render(string $template, array $params): string;

    public function addService(string $name, object $object): void;
}