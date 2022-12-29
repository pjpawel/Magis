<?php

namespace pjpawel\Magis\View;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
enum Event
{
    case BeforeRun;
    case AfterRun;
    case AfterRender;
}
