<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 16.08.2018
 * Time: 16:58
 */

namespace core;


use core\base\ResponseInterface;

class ResponseFactory
{
    const HTML = 'html';
    const JSON = 'json';
    const XML = 'xml';
    const FILE = 'file';

    /**
     * @param $type
     * @return ResponseInterface
     */
    public static function get($type)
    {
        switch ($type)
        {
            case self::HTML;
                return new HtmlResponse();
            case self::JSON;
            case self::XML;
            case self::FILE;
            default:
                throw new \InvalidArgumentException("No response for type {$type}");
        }
    }
}