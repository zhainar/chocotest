<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:22
 */

namespace core\base;


trait SingletonTrait
{
    protected static $instance;

    /**
     * @return static
     */
    final public static function instance()
    {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static;
    }

    /**
     * Singleton constructor.
     */
    final private function __construct() 
    {
        $this->init();
    }
    
    protected function init() {}
    
    final private function __wakeup() {}
    final private function __clone() {}
}