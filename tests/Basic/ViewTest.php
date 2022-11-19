<?php

namespace pjpawel\Magis\Test\Basic;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\View;

class ViewTest extends TestCase
{

    public const TEMPLATE_DIR = __DIR__ . '/../resources/php';
    public const HTML_DIR = __DIR__ . '/../resources/html';

    public function testSimpleView(): void
    {
        $view = new View(self::TEMPLATE_DIR);
        $content = $view->render('base.php');
        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $content);
    }
}
