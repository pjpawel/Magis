<?php

namespace pjpawel\Magis\Test;

use PHPUnit\Framework\TestCase;
use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\View\DirectView;
use pjpawel\Magis\ViewDispatcherService;

class ViewDispatcherServiceTest extends TestCase
{

    public const TEMPLATE_DIR = __DIR__ . '/resources/php';
    public const HTML_DIR = __DIR__ . '/resources/html';
    public const DIRECT_DIR = '/direct/';

    public function testServiceDispatcherDirectView(): void
    {
        $viewService = new ViewDispatcherService('direct', self::TEMPLATE_DIR . self::DIRECT_DIR);

        $start = microtime(true);

        $index = $viewService->render('index.php', ['title' => 'Title', 'body' => null]);

        $time = microtime(true) - $start;
        $this->assertLessThanOrEqual(0.001, $time);

        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $index);
    }

    public function testServiceDispatcherDirectViewBasedOnViewClassName(): void
    {
        $viewService = new ViewDispatcherService(DirectView::class, self::TEMPLATE_DIR . self::DIRECT_DIR);

        $start = microtime(true);

        $index = $viewService->render('index.php', ['title' => 'Title', 'body' => null]);

        $time = microtime(true) - $start;
        $this->assertLessThanOrEqual(0.001, $time);

        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $index);
    }

    public function testServiceDispatcherIncorrectViewModeName(): void
    {
        $modeName = 'indirect';
        $this->expectException(TemplateException::class);
        $this->expectExceptionMessage('Unknown view mode ' . $modeName);
        $viewService = new ViewDispatcherService($modeName, self::TEMPLATE_DIR . self::DIRECT_DIR);
    }

    public function testTemplateNameWithoutExtension(): void
    {
        $viewService = new ViewDispatcherService(DirectView::class, self::TEMPLATE_DIR . self::DIRECT_DIR);

        $start = microtime(true);

        $index = $viewService->render('index', ['title' => 'Title', 'body' => null]);

        $time = microtime(true) - $start;
        $this->assertLessThanOrEqual(0.001, $time);

        $this->assertStringEqualsFile(self::HTML_DIR . '/base.html', $index);
    }
}
