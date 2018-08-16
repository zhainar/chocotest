<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 16.08.2018
 * Time: 16:50
 */

namespace core\base;


interface ResponseInterface
{
    /**
     * @return mixed
     */
    public function render();

    /**
     * @param array $data
     * @return mixed
     */
    public function setData(array $data);

    /**
     * @param $template
     * @return mixed
     */
    public function setTemplate($template);
}