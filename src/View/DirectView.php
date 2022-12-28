<?php

namespace pjpawel\Magis\View;

use pjpawel\Magis\Exception\TemplateException;

/**
 * @author Paweł Podgórski <pawel.jan.podgorski@gmail.com>
 */
class DirectView extends AbstractView
{

    /**
     * @inheritDoc
     */
    public function render(string $template, array $params = []): string
    {
        $template = $this->loadTemplate($template);
        $this->params = array_merge_recursive($params, $this->params);

        return $this->renderPhpFile($template, $this->params);
    }

}