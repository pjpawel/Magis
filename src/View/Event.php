<?php

namespace pjpawel\Magis\View;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
enum Event
{

    case BeginPage;
    case Head;
    case BeforeBody;
    case BeginBody;
    case EndBody;
    case EndPage;
    case AfterRender;

    /**
     * @return list<self> All events in order
     */
    public static function getAllEvents(): array
    {
        return [
            self::BeginPage,
            self::Head,
            self::BeforeBody,
            self::BeginBody,
            self::EndBody,
            self::EndPage,
            self::AfterRender
        ];
    }
}
