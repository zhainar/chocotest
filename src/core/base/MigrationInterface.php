<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:49
 */

namespace core\base;


interface MigrationInterface
{
    /**
     * @return void
     */
    public function execute();

    /**
     * @return void
     */
    public function revert();
}