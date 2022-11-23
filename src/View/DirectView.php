<?php

namespace pjpawel\Magis\View;

use pjpawel\Magis\Exception\TemplateException;

class DirectView extends AbstractView
{

    /**
     * @param string $template
     * @param array $params
     * @return string
     * @throws TemplateException
     */
    public function render(string $template, array $params = []): string
    {
        $template = $this->loadTemplate($template);
        $this->params = array_merge_recursive($params, $this->params);

        return $this->renderPhpFile($template, $this->params);
    }

}