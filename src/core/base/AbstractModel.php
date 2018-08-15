<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 12:30
 */

namespace core\base;


use core\Connection;

abstract class AbstractModel
{
    /**
     * @var bool
     */
    protected $is_new_record;

    /**
     * AbstractModel constructor.
     * @param array $data
     * @param bool $is_new_record
     */
    public function __construct(array $data = [], $is_new_record = true)
    {
        if (!empty($data)) {
            $this->setPropertiesFromArray($data);
        }

        $this->is_new_record = (bool) $is_new_record;
    }

    /**
     * @throws \Exception
     * @return string
     */
    public static function tableName()
    {
        throw new \Exception('Table name not found');
    }

    /**
     * @throws \Exception
     * @return array
     */
    public static function labels()
    {
        throw new \Exception('Labels not found');
    }

    /**
     * @return string
     */
    public static function getPrimaryKeyField()
    {
        return 'id';
    }

    /**
     * @param array $data
     */
    protected function setPropertiesFromArray(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $setter = 'set' . $this->getAliasName($key);
                if (method_exists($this, $setter)) {
                    $this->$setter($value);
                } else {
                    throw new \BadMethodCallException("Setter \"{$setter}\" not found");
                }
            }
        }
    }

    /**
     * @param $field
     * @return string
     */
    protected function getAliasName($field)
    {
        $words = explode('_', $field);
        $words = array_map(function ($value) { $value[0] = mb_strtoupper($value[0]); return $value; }, $words);
        return implode('', $words);
    }

    /**
     * @return array
     * @throws \Exception
     */
    public static function findAll()
    {
        $result = [];
        $query = "select * from " . static::tableName();
        $data = Connection::instance()->query($query)->fetchAll();
        foreach ($data as $value) {
            $result[] = new static($value, false);
        }
        return $result;
    }

    /**
     * @param $id
     * @return null|static
     * @throws \Exception
     */
    public static function findById($id)
    {
        $query = "select * from " . static::tableName() . " where " . static::getPrimaryKeyField() . " = ?";
        $params = [(int)$id];
        $data = Connection::instance()->query($query, $params)->fetchAll();
        if (!empty($data)) {
            return new static(array_shift($data), false);
        }
        return null;
    }

    /**
     * @return int
     */
    public function save()
    {
        return ($this->is_new_record) ? $this->insert() : $this->update();
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function insert()
    {
        $fields = array_keys(static::labels());
        $query = "insert into " . static::tableName() . " (" . implode(', ', $fields).") values (" . implode(', ', array_fill(0, count($fields), '?')) . ")";
        $values = [];
        foreach ($fields as $field) {
            $getter = 'get' . $this->getAliasName($field);
            if (method_exists($this, $getter)) {
                $value = $this->$getter();
                if ($value instanceof \DateTime) {
                    $value = $value->getTimestamp();
                }
                $values[] = $value;
            } else {
                throw new \BadMethodCallException("Getter \"{$getter}\" not found");
            }
        }
        $statement = Connection::instance()->query($query, $values);
        return $statement->rowCount();
    }

    /**
     * @return int
     * @throws \Exception
     */
    protected function update()
    {
        $fields = array_keys(static::labels());
        $set_placeholders = array_map(function ($field) { return "{$field} = ?"; }, $fields);
        $query = "update " . static::tableName() . " set " . implode(', ', $set_placeholders) . " where " . $this->getPrimaryKeyField() . " = ?";
        $values = [];
        foreach ($fields as $field) {
            $getter = 'get' . $this->getAliasName($field);
            if (method_exists($this, $getter)) {
                $value = $this->$getter();
                if ($value instanceof \DateTime) {
                    $value = $value->getTimestamp();
                }
                $values[] = $value;
            } else {
                throw new \BadMethodCallException("Getter \"{$getter}\" not found");
            }
        }
        $pk_getter = 'get' . $this->getAliasName($this->getPrimaryKeyField());
        if (!method_exists($this, $pk_getter)) {
            throw new \BadMethodCallException("Getter \"{$pk_getter}\" not found");
        }
        $values[] = $this->$pk_getter();
        $statement = Connection::instance()->query($query, $values);
        return $statement->rowCount();
    }
}