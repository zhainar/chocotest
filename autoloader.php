<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:39
 */

spl_autoload_register(function($classname) {
    include_once BASEPATH . "/src/{$classname}.php";;
});