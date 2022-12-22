<?php

namespace pjpawel\Magis\Test\Helper;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\Helper\Style;

class StyleTest extends TestCase
{

    public function testShow(): void
    {
        $css = <<<CSS
            .style-to-block {
                overflow: auto;
            }
            CSS;
        $style = new Style($css);
        $this->assertEquals(
            '<style>' . $css . '</style>',
            $style->show()
        );
    }

}