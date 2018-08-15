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
     * @var
     */
    protected $last_query;

    /**
     * @var \PDO
     */
    protected $connection;

    protected function init()
    {
        $this->connection = new \PDO('mysql:dbname=chocotest;host=127.0.0.1', 'chocotest', 'chocotest', [
            \PDO::ATTR_AUTOCOMMIT => false,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ]);
    }

    /**
     * @param $query
     * @param array $params
     * @return \PDOStatement
     */
    public function query($query, array $params = [])
    {
        $statement = $this->connection->prepare($query);
        $this->last_query = $query;
        if ($statement->execute($params)) {
            return $statement;
        } else {
            throw new \PDOException(implode(': ', $statement->errorInfo()));
        }
    }

    /**
     * @return bool
     */
    public function transaction()
    {
        return $this->connection->beginTransaction();
    }

    /**
     * @return bool
     */
    public function commit()
    {
        return $this->connection->commit();
    }

    /**
     * @return bool
     */
    public function rollback()
    {
        return $this->connection->rollBack();
    }
}