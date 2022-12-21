<?php

namespace pjpawel\Magis\View;

use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\Helper\AppContainerInterface;
use pjpawel\Magis\Helper\Script;
use pjpawel\Magis\Helper\Style;
use pjpawel\Magis\Helper\Tag;
use pjpawel\Magis\Template;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class MagicView extends AbstractView
{

    /**
     * Content of html
     * @var string
     */
    private string $content = '';
    /**
     * Registered events
     * @var array<string, callable>
     */
    private array $events = [];
    /**
     * @var list<Tag>
     */
    private array $headTags = [];
    /**
     * @var list<string>
     */
    private array $jsFiles = [];
    /**
     * @var list<string>
     */
    private array $cssFiles = [];
    /**
     * @var list<Script>
     */
    private array $jsBlocks = [];
    /**
     * @var list<Style>
     */
    private array $cssBlocks = [];
    private string $language = 'en';
    private string $charset = 'UTF-8';
    private string $title = '';


    public function __construct(string $templateDir)
    {
        parent::__construct($templateDir);
        $this->registerDefaultTriggers();
    }

    protected function registerDefaultTriggers()
    {
        $this->registerTrigger(
            Event::BeforeRun,
            function (MagicView $view) {
                $view->content .= '<!DOCTYPE html><html lang="' . $view->loadLanguage() . '">';
                $view->content .= '<head><title>' . $view->getTitle() . '</title><meta charset="' . $view->getCharset() . '">';
                foreach ($view->getHeadTags() as $tag) {
                    $view->content .= $tag->show() . PHP_EOL;
                }
                foreach ($view->getCssBlocks() as $css) {
                    $view->content .= $css->show() . PHP_EOL;
                }
                $view->content .= '</head>';
                $view->content .= '<body>';
                foreach ($view->getCssFiles() as $css) {
                    $view->content .= $css . PHP_EOL;
                }
            }
        );
        $this->registerTrigger(
            Event::AfterRun,
            function (MagicView $view) {
                foreach ($view->getJsFiles() as $js) {
                    $view->content .= $js . PHP_EOL;
                }
                foreach ($view->getJsBlocks() as $js) {
                    $view->content .= $js->show() . PHP_EOL;
                }
                $view->content .= '</body>';
                $view->content .= '</html>';
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function render(string $template, array $params = []): string
    {
        $template = Template::resolveTemplatePath($this->templateDir, $template);
        $this->params = array_merge_recursive($params, $this->params);
        $content = $this->renderPhpFile($template, $this->params);

        $this->callTrigger(Event::BeforeRun);
        $this->content .= $content;
        $this->callTrigger(Event::AfterRun);
        $this->callTrigger(Event::AfterRender);

        return $this->content;
    }

    public function extend(string $template, array $params = []): string
    {
        $template = Template::resolveTemplatePath($this->templateDir, $template);
        return $this->renderPhpFile($template, $params);
    }

    /**
     * @param Event $event
     * @param callable $function
     * @return void
     */
    public function registerTrigger(Event $event, callable $function): void
    {
        $this->events[$event->name] = $function;
    }

    public function callTrigger(Event $event): void
    {
        if (array_key_exists($event->name, $this->events)) {
            call_user_func($this->events[$event->name], $this);
        }
    }

    /**
     * @return string
     */
    public function loadLanguage(): string
    {
        if (isset($this->app) && is_subclass_of($this->app, AppContainerInterface::class)) {
            $this->language = $this->app->get('request')->getLocale();
        }
        return $this->language;
    }

    /**
     * @return list<Tag>
     */
    public function getHeadTags(): array
    {
        return $this->headTags;
    }

    /**
     * @param Tag $tag
     */
    public function addHeadTag(Tag $tag): void
    {
        $this->headTags[] = $tag;
    }

    /**
     * This will check if title is set,
     * WARNING! This will not override title that was set!
     *
     * @param string $title
     * @return void
     */
    public function setTitle(string $title): void
    {
        if (!isset($this->title)) {
            $this->title = $title;
        }
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCharset(): string
    {
        return $this->charset;
    }

    /**
     * Clears workspace
     *
     * @return void
     */
    public function clear(): void
    {
        $this->headTags = [];
        $this->jsFiles = [];
        $this->cssFiles = [];
        $this->jsBlocks = [];
        $this->cssBlocks = [];
    }

    /**
     * Should be injected to the view as PackageInterface
     *
     * @param $path
     * @return string
     * @throws TemplateException
     * @deprecated
     */
    public function asset($path): string
    {
        if (!isset($this->asset)) {
            throw new TemplateException();
        }
        return $this->asset->getUrl($path);
    }

    /**
     * @return list<string>
     */
    public function getCssFiles(): array
    {
        return $this->cssFiles;
    }

    /**
     * @return list<Style>
     */
    public function getCssBlocks(): array
    {
        return $this->cssBlocks;
    }

    /**
     * @return list<string>
     */
    public function getJsFiles(): array
    {
        return $this->jsFiles;
    }

    /**
     * @return list<Script>
     */
    public function getJsBlocks(): array
    {
        return $this->jsBlocks;
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset): void
    {
        $this->charset = $charset;
    }

    /**
     * @param string $cssFile
     * @return void
     */
    public function addCssFile(string $cssFile): void
    {
        $this->cssFiles[] = $cssFile;
    }

    /**
     * @param string $cssFile
     * @return void
     */
    public function addJsFile(string $cssFile): void
    {
        $this->jsFiles[] = $cssFile;
    }

    /**
     * @param Style $cssBlock
     * @return void
     */
    public function addCssBlock(Style $cssBlock): void
    {
        $this->cssBlocks[] = $cssBlock;
    }

    /**
     * @param Script $jsBlock
     * @return void
     */
    public function addJsBlock(Script $jsBlock): void
    {
        $this->jsBlocks[] = $jsBlock;
    }


}