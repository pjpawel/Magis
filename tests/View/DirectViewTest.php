<?php

namespace pjpawel\Magis\Test\View;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\Resources\EchoService;
use pjpawel\Magis\View\DirectView;

class DirectViewTest extends TestCase
{

    public const TEMPLATE_DIR = __DIR__ . '/../../resources/php';
    public const HTML_DIR = __DIR__ . '/../../resources/html';

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

    public function testManualSnippetView(): void
    {
        $view = new DirectView(self::TEMPLATE_DIR . '/snippet');
        $content = $view->render('index.php', ['title' => 'Title', 'snippetName' => 'view snippet']);
        $this->assertStringEqualsFile(self::HTML_DIR . '/snippet.html', $content);
    }

    //TODO:
    /*public function testAutomaticSnippetView(): void
    {

    }*/

    public function testEchoService()
    {
        $view = new DirectView(self::TEMPLATE_DIR . '/service');
        $view->addService('echo', new EchoService());
        $content = $view->render('base.php', ['title' => 'Title',]);
        $this->assertStringEqualsFile(self::HTML_DIR . '/service.html', $content);
    }
}
