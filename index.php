<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:08
 */

define('BASEPATH', __DIR__);
define('DEBUG', true);

require_once __DIR__ . '/autoloader.php';

(new Application())->run();