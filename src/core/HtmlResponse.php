<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 16.08.2018
 * Time: 17:01
 */

namespace core;


use core\base\ResponseInterface;

class HtmlResponse implements ResponseInterface
{
    /** @var array  */
    protected $data = [];
    protected $template;
    protected $wrapper = 'wrapper';
    
    public function render()
    {
        if (!empty($this->data)) {
            extract($this->data);
        }
        ob_start();
        include_once $this->template;
        $content = ob_get_clean();
        include_once $this->getWrapperTemplate();
    }

    public function setData(array $data = [])
    {
        $this->data = $data;
    }

    public function setTemplate($template)
    {
        $template = preg_replace('/^\//', '', $template);
        $template = preg_replace('/\.php$/', '', $template);
        $template_path = BASEPATH . "/templates/{$template}.php";
        if (!file_exists($template_path)) {
            throw new \InvalidArgumentException("Template \"{$template}\" not found");
        }
        
        $this->template = $template_path;
    }

    protected function getWrapperTemplate()
    {
        return BASEPATH . "/templates/{$this->wrapper}.php";
    }
}