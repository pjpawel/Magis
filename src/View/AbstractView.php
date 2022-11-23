<?php

namespace pjpawel\Magis\View;

use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\Template;

abstract class AbstractView
{

    protected string $templatePath;
    protected array $params = [];

    public function __construct(string $templatePath)
    {
        if (str_ends_with($templatePath, '/')) {
            $this->templatePath = $templatePath;
        } else {
            $this->templatePath = $templatePath . '/';
        }
    }

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws TemplateException
     */
    abstract public function render(string $template, array $params = []): string;

    /**
     * @param string $template
     * @return Template
     */
    protected function loadTemplate(string $template): Template
    {
        return new Template($this->templatePath . $template);
    }

    /**
     * @param Template $template
     * @param array $params
     * @return bool|string
     * @throws TemplateException
     */
    protected function renderPhpFile(Template $template, array $params): bool|string
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $template->getTemplatePath();
            return ob_get_clean();
        } catch (\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            $message = 'Loading template exception: ';
            if (str_starts_with($e->getMessage(), $message)) {
                $message = '';
            }
            throw new TemplateException($message . $e->getMessage(), 0, $e);
        }
    }

    /**
     * @param string $name
     * @param object $object
     * @return void
     */
    public function addService(string $name, object $object): void
    {
        $this->$name = $object;
    }

}