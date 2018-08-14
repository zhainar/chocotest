<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 14.08.2018
 * Time: 23:14
 */

namespace core;


use core\base\SingletonTrait;

class Connection
{
    use SingletonTrait;

    /**
     * @var \PDO
     */
    protected $connection;

    protected function init()
    {
        $this->connection = new \PDO('mysql:dbname=chocotest;host=127.0.0.1', 'chocotest', 'chocotest');
    }

    /**
     * @param $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query($query, array $params = [])
    {
        $statement = $this->connection->prepare($query);
        if ($statement->execute($params)) {
            return $statement;
        } else {
            throw new \PDOException(implode(': ', $statement->errorInfo()));
        }
    }
}