<?php

namespace pjpawel\Magis;

use pjpawel\Magis\Exception\TemplateException;

/**
 * Class provides dispatcher to different views class modes,
 * should be used as Service in Dependency Injection
 */
class ViewDispatcherService
{

    /**
     * @var string|null Class namespace
     */
    private ?string $defaultViewClass = null;
    /**
     * @var string Absolute path to templates directory
     */
    private string $templatePath;
    /**
     * @var array<string, object> Services to inject into view
     */
    private array $services;
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
     * @param array $services
     * @throws TemplateException
     */
    public function __construct(string $viewMode, string $templatePath, array $services = [])
    {
        $this->setDefaultViewMode($viewMode);
        $this->templatePath = $templatePath;
        $this->services = $services;
    }

    /**
     * @param string $templateName Template name e.g. 'index.php'
     * @param array<string, mixed> $params Params that will be used as variables.
     *  Key will be used as variable name, and value as var value
     * @param string|null $viewMode Set different view mode
     * @return string Rendered content
     * @throws TemplateException
     */
    public function render(string $templateName, array $params = [], ?string $viewMode = null): string
    {
        if ($viewMode === null) {
            $viewClass = $this->getDefaultViewClass();
        } else {
            $viewClass = $this->getViewClassFromMode($viewMode);
        }
        $view = new $viewClass($this->templatePath);

        foreach ($this->services as $name => $service) {
            $view->addService($name, $service);
        }

        return $view->render($templateName, $params);
    }

    /**
     * @param string $mode
     * @return void
     * @throws TemplateException
     */
    public function setDefaultViewMode(string $mode): void
    {
        $this->defaultViewClass = $this->getViewClassFromMode($mode);
    }

    /**
     * @param $mode
     * @return string
     * @throws TemplateException
     */
    protected function getViewClassFromMode($mode): string
    {
        if (isset(self::VIEW_MODE[$mode])) {
            $class = self::VIEW_MODE[$mode];
        } elseif (false !== $key = array_search($mode, self::VIEW_MODE)) {
            $class = self::VIEW_MODE[$key];
        } else {
            throw new TemplateException('Unknown view mode ' . $mode);
        }
        if (!is_subclass_of($class, ViewInterface::class)) {
            throw new TemplateException("View mode doesn't implements " . ViewInterface::class);
        }
        return $class;
    }

    /**
     * @return string
     */
    public function getDefaultViewClass(): string
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

    /**
     * @return string
     */
    public function getTemplatePath(): string
    {
        return $this->templatePath;
    }

    /**
     * @param string $alias
     * @param object $object
     * @return void
     */
    public function addService(string $alias, object $object): void
    {
        $this->services[$alias] = $object;
    }

}