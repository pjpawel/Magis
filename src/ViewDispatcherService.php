<?php

namespace pjpawel\Magis;

use pjpawel\Magis\Exception\TemplateException;

/**
 * Class provides dispatcher to different views class modes,
 * should be used as Service in Dependency Injection
 */
class ViewDispatcherService
{

    private ?string $defaultViewClass = null;
    private string $templatePath;

    /**
     * Dispatcher options of views mode
     * All classes must implement ViewInterface
     */
    public const VIEW_MODE = [
        'direct' => DirectView::class,
    ];

    /**
     * @param string $viewMode
     * @param string $templatePath
     * @throws TemplateException
     */
    public function __construct(string $viewMode, string $templatePath)
    {
        $this->setDefaultViewMode($viewMode);
        $this->templatePath = $templatePath;
    }

    /**
     * @param string $view
     * @param array $params
     * @param string|null $viewMode
     * @return string
     * @throws TemplateException
     */
    public function render(string $view, array $params = [], ?string $viewMode = null): string
    {
        if ($viewMode === null) {
            $viewMode = $this->getDefaultViewMode();
        }
        $viewObject = new $viewMode($this->templatePath);

        return $viewObject->render($view, $params);
    }

    /**
     * @param string $mode
     * @return void
     * @throws TemplateException
     */
    public function setDefaultViewMode(string $mode): void
    {
        if (isset(self::VIEW_MODE[$mode])) {
            $this->defaultViewClass = self::VIEW_MODE[$mode];
        } elseif (false !== $key = array_search($mode, self::VIEW_MODE)) {
            $this->defaultViewClass = self::VIEW_MODE[$key];
        } else {
            throw new TemplateException('Unknown view mode ' . $mode);
        }
        if (!is_subclass_of($this->defaultViewClass, ViewInterface::class)) {
            throw new TemplateException("View mode doesn't implements " . ViewInterface::class);
        }
    }

    /**
     * @return string
     */
    public function getDefaultViewMode(): string
    {
        return $this->defaultViewClass;
    }

    /**
     * @param string $templatePath
     */
    public function setTemplatePath(string $templatePath): void
    {
        $this->templatePath = $templatePath;
    }

}