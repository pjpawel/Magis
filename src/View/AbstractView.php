<?php

namespace pjpawel\Magis\View;

use _PHPStan_5c71ab23c\Nette\Neon\Exception;
use pjpawel\Magis\Exception\TemplateException;
use pjpawel\Magis\Template;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
abstract class AbstractView
{

    protected string $templateDir;
    /**
     * @var array<string,mixed>
     */
    protected array $params = [];

    public function __construct(string $templateDir)
    {
        if (str_ends_with($templateDir, '/')) {
            $this->templateDir = $templateDir;
        } else {
            $this->templateDir = $templateDir . '/';
        }
    }

    /**
     * @param string $template
     * @param array<string,mixed> $params
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
        return new Template($this->templateDir . $template);
    }

    /**
     * @param Template $template
     * @param array<string,mixed> $params
     * @return string
     * @throws TemplateException
     */
    protected function renderPhpFile(Template $template, array $params): string
    {
        $_obInitialLevel_ = ob_get_level();
        ob_start();
        ob_implicit_flush(false);
        extract($params, EXTR_OVERWRITE);
        try {
            require $template->getTemplatePath();
            $buffer = ob_get_clean();
            if ($buffer === false) {
                throw new Exception('Buffer is not active');
            }
            return $buffer;
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