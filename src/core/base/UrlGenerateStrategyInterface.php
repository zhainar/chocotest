<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 13:30
 */

namespace core\base;


interface UrlGenerateStrategyInterface
{
    /**
     * @param $id
     * @param $name
     * @return string
     */
    public function generate($id, $name);
}