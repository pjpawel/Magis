<?php

namespace pjpawel\Magis;

use pjpawel\Magis\Exception\TemplateException;

class Template
{

    private string $templatePath;
    private ?string $content = null;

    public function __construct(string $templatePath)
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
     * @return string
     * @throws TemplateException
     */
    public function getTemplateContent(): string
    {
        if ($this->content == null) {
            $this->loadTemplateContent();
        }
        return $this->content;
    }

    /**
     * @return void
     * @throws TemplateException
     */
    private function loadTemplateContent(): void
    {
        if (!is_file($this->templatePath)) {
            throw new TemplateException('There is no template with given name');
        }
        $content = file_get_contents($this->templatePath);
        if ($content === false) {
            throw new TemplateException('Cannot get content of template');
        }
        $this->content = $content;
    }

}