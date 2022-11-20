<?php

namespace pjpawel\Magis\Test;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\DirectView;
use pjpawel\Magis\Exception\TemplateException;

class DirectViewTest extends TestCase
{

    public const TEMPLATE_DIR = __DIR__ . '/../resources/php';
    public const HTML_DIR = __DIR__ . '/../resources/html';

    public function testSimpleView(): void
    {
        $view = new DirectView(self::TEMPLATE_DIR);
        $content = $view->render('base.php', ['title' => 'Title', 'body' => null]);
        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $content);
    }

    public function testNestedView(): void
    {
        $view = new DirectView(self::TEMPLATE_DIR);
        $content = $view->render('index.php', ['title' => 'Title', 'body' => null]);
        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $content);
    }

    public function testSimpleViewWithoutParameter(): void
    {
        $this->expectException(TemplateException::class);
        $this->expectExceptionMessage('Loading template exception: Undefined variable $body');
        $view = new DirectView(self::TEMPLATE_DIR);
        $content = $view->render('index.php', ['title' => 'Title']);
    }
}
