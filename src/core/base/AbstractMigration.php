<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 0:21
 */

namespace core\base;


class AbstractMigration implements MigrationInterface
{
    public function execute()
    {
        throw new \BadMethodCallException();
    }
    
    public function revert()
    {
        throw new \BadMethodCallException();
    }
}