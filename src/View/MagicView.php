<?php

namespace pjpawel\Magis\View;

use pjpawel\Magis\Helper\Tag;

class MagicView extends AbstractView
{

    /**
     * @var string Content of html
     */
    private string $content = '';
    /**
     * @var array<string, callable> Registered events
     */
    private array $events = [];
    /**
     * @var list<Tag>
     */
    private array $headTags = [];
    private string $language = 'en';
    private string $title = '';


    public function __construct(string $templateDir)
    {
        parent::__construct($templateDir);
        $this->registerTrigger(Event::BeginPage, function ($view) {
            $view->content .= '<!DOCTYPE html><html lang="' . $view->getLanguage() . '">';
        });
        $this->registerTrigger(Event::Head, function ($view) {
            $view->content.= '<head><title>' . $view->getTitle() . '</title>';
            foreach ($view->getHeadTags() as $tag) {
                $view->content .= $tag::show() . PHP_EOL;
            }
            $view->content.= '<head>';
        });
        $this->registerTrigger(Event::BeginBody, function ($view) {$view->content .= '<body>';});
        $this->registerTrigger(Event::EndBody, function ($view) {$view->content .= '</body>';});
        $this->registerTrigger(Event::EndPage, function ($view) {$view->content .= '</html>';});
    }

    /**
     * @inheritDoc
     */
    public function render(string $template, array $params = []): string
    {
        $this->trigger(Event::BeginPage);
        $template = $this->loadTemplate($template);
        $this->params = array_merge_recursive($params, $this->params);
        $content = $this->renderPhpFile($template, $this->params);
        $this->trigger(Event::Head);
        $this->trigger(Event::BeforeBody);
        $this->trigger(Event::BeginBody);
        $this->content .= $content;
        $this->trigger(Event::EndBody);
        $this->trigger(Event::EndPage);
        $this->trigger(Event::AfterRender);
        return $this->content;
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

    public function trigger(Event $event): void
    {
        if (array_key_exists($event->name, $this->events)) {
            call_user_func($this->events[$event->name], $this);
        }
    }

    public function setLanguage(string $language): void
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getLanguage(): string
    {
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
    public function loadHeadTag(Tag $tag): void
    {
        $this->headTags[] = $tag;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }


}