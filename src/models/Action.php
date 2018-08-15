<?php
/**
 * Created by PhpStorm.
 * User: uzer
 * Date: 15.08.2018
 * Time: 1:48
 */

namespace models;


use core\base\AbstractModel;
use core\base\UrlGenerateStrategyInterface;
use core\UrlGenerateBaseStrategy;

class Action extends AbstractModel
{
    /** @var  int */
    private $id;
    /** @var  string */
    private $name;
    /** @var  \DateTime */
    private $start_date;
    /** @var  \DateTime */
    private $end_date;
    /** @var  string */
    private $status;

    /**
     * @return string
     */
    public static function tableName()
    {
        return 'action';
    }

    /**
     * @return array
     */
    public static function labels()
    {
        return [
            'id' => 'ID акции',
            'name' => 'Название акции',
            'start_date' => 'Дата начала акции',
            'end_date' => 'Дата окончания',
            'status' => 'Статус'
        ];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = (int) $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * @param mixed $start_date
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $this->_getDatetime($start_date);
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * @param mixed $end_date
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $this->_getDatetime($end_date);
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        if (!in_array($status, ['On', 'Off'])) {
            throw new \UnexpectedValueException("Wrong parameter \"{$status}\"");
        }
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->generateUrl(new UrlGenerateBaseStrategy());
    }

    /**
     * @param UrlGenerateStrategyInterface $strategy
     * @return string
     */
    protected function generateUrl(UrlGenerateStrategyInterface $strategy)
    {
        return $strategy->generate($this->getId(), $this->getName());
    }

    /**
     * @param $value
     * @return \DateTime
     */
    protected function _getDatetime($value)
    {
        if (is_numeric($value)) {
            $value = date('Y-m-d H:i:s', (int) $value);
        }
        return new \DateTime($value);
    }
}