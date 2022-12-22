<?php

namespace pjpawel\Magis;

use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\View\AbstractView;
use pjpawel\Magis\View\DirectView;
use pjpawel\Magis\View\MagicView;

/**
 * Class provides dispatcher to different views class modes,
 * should be used as Service in Dependency Injection
 *
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class ViewDispatcherService
{

    /**
     * @var string Class namespace
     */
    private string $defaultViewClass;
    /**
     * @var string Absolute path to templates directory
     */
    private string $templateDir;
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
        'magic' => MagicView::class,
    ];
    /**
     * Allows to pass php template name without extension
     */
    private const DEFAULT_EXTENSION = '.php';

    /**
     * @param string $viewMode
     * @param string $templateDir
     * @param array<string, object> $services
     * @throws TemplateException
     */
    public function __construct(string $viewMode, string $templateDir, array $services = [])
    {
        $this->setDefaultViewMode($viewMode);
        $this->templateDir = $templateDir;
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
            $viewClass = $this->defaultViewClass;
        } else {
            $viewClass = $this->getViewClassFromMode($viewMode);
        }
        $this->ensureTemplateNameHasExtension($templateName);

        /** @var AbstractView $view */
        $view = new $viewClass($this->templateDir);

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
     * @param string $mode
     * @return string
     * @throws TemplateException
     */
    protected function getViewClassFromMode(string $mode): string
    {
        if (isset(self::VIEW_MODE[$mode])) {
            $class = self::VIEW_MODE[$mode];
        } elseif (false !== $key = array_search($mode, self::VIEW_MODE)) {
            $class = self::VIEW_MODE[$key];
        } else {
            throw new TemplateException('Unknown view mode ' . $mode);
        }
        if (!is_subclass_of($class, AbstractView::class)) {
            throw new TemplateException("View mode doesn't inherit from " . AbstractView::class);
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
     * @param string $templateDir
     */
    public function setTemplateDir(string $templateDir): void
    {
        $this->templateDir = $templateDir;
    }

    /**
     * @return string
     */
    public function getTemplateDir(): string
    {
        return $this->templateDir;
    }

    /**
     * Service can be passed as :
     *  object: it will be passed directly
     *  string: service will be given from container //NOT NOW!
     *
     * @param string $alias
     * @param object $object   // Object or alias from container
     * @return void
     */
    public function addService(string $alias, object$object): void
    {
        $this->services[$alias] = $object;
    }

    /**
     * Provide possibility to pass name of template without php extension
     *
     * @param string $templateName
     * @return void
     */
    protected function ensureTemplateNameHasExtension(string &$templateName): void
    {
        if (!str_ends_with($templateName, self::DEFAULT_EXTENSION)) {
            $templateName .= self::DEFAULT_EXTENSION;
        }
    }

}