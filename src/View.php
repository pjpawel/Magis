<?php

namespace pjpawel\Magis;

use pjpawel\Magis\Exception\TemplateException;

class View
{

    protected string $templatePath;
    protected ?string $parent = null;
    protected array $arguments = [];

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
    public function render(string $template, array $params = []): string
    {
        $template = $this->loadTemplate($template);

        return $this->renderPhpFile($template, $params);
    }

    /**
     * @param string $template
     * @return Template
     */
    private function loadTemplate(string $template): Template
    {
        return new Template($this->templatePath . $template);
    }

    /**
     * @param Template $template
     * @param array $params
     * @return bool|string
     * @throws TemplateException
     */
    public function renderPhpFile(Template $template, array $params): bool|string
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $template->getTemplatePath();
            return ob_get_clean();
        } catch (\Exception|\Throwable $e) {
            while (ob_get_level() > $_obInitialLevel_) {
                if (!@ob_end_clean()) {
                    ob_clean();
                }
            }
            throw new TemplateException('Loading template exception', 0, $e);
        }
    }

}