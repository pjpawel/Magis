<?php

namespace pjpawel\Magis\Helper;

interface AppContainerInterface
{

    public function get(string $id): mixed;

}