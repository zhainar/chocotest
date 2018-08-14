<?php

/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:43
 */
class Application
{
    public function run()
    {
        try
        {
            (new \core\MigrationsManager())->check();
        }
        catch (Exception $e)
        {
            if (defined('DEBUG') && DEBUG) {
                echo '<pre>';
                echo $e->getMessage() . PHP_EOL . PHP_EOL . $e->getTraceAsString();
                echo '</pre>';
                exit;
            }
        }
    }
}