<?php

namespace pjpawel\Magis\Test\Helper;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\Helper\Style;

class StyleTest extends TestCase
{

    private const CSS = <<<CSS
            .style-to-block {
                overflow: auto;
            }
            CSS;

    public function testShow(): void
    {
        $style = new Style(self::CSS);
        $this->assertEquals(
            '<style>' . self::CSS . '</style>',
            $style->show()
        );
    }

    public function testCreateAndShow(): void
    {
        $this->assertEquals(
            '<style>' . self::CSS . '</style>',
            Style::createAndShow(self::CSS)
        );
    }

}