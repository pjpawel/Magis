<?php

namespace pjpawel\Magis\Test\View;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\View\MagicView;

class MagicViewTest extends TestCase
{
    public const TEMPLATE_DIR = __DIR__ . '/../resources/php/magic';
    public const HTML_DIR = __DIR__ . '/../resources/html';

    public function getHtmlContent(string $name): string
    {
        return file_get_contents(self::HTML_DIR . '/' . $name);
    }

    public function testBase(): void
    {
        $view = new MagicView(self::TEMPLATE_DIR);
        $view->render('base.php');
        $this->assertStringContainsString(
            '<title>Title</title>',
            $this->getHtmlContent('base.html'));
    }

    public function testSnippet(): void
    {
        $view = new MagicView(self::TEMPLATE_DIR);
        $view->render('snippet/index.php');
        $this->assertStringContainsString(
            'This is snippet with name: view snippet',
            $this->getHtmlContent('snippet.html'));
    }
}