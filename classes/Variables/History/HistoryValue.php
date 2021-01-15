<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\History;

use Ms\Core\Entity\Type\Date;
use Ms\Dobrozhil\Variables\General\HistoryValueInterface;

/**
 * Класс Ms\Dobrozhil\Variables\History\HistoryValue
 * Историческое значение переменной
 */
class HistoryValue implements HistoryValueInterface
{
    /** @var null|int */
    protected $id = null;
    /** @var null|string */
    protected $variableName = null;
    /** @var null|mixed */
    protected $value = null;
    /** @var null|int */
    protected $createdBy = null;
    /** @var null|Date */
    protected $createdDate = null;

    /**
     * @inheritDoc
     */
    public static function createFromArray (array $arFields): HistoryValueInterface
    {
        $object = new self ();
        if (array_key_exists('ID',$arFields))
        {
            $object->setID($arFields['ID']);
        }
        if (array_key_exists('VARIABLE_NAME',$arFields))
        {
            $object->setVariableName($arFields['VARIABLE_NAME']);
        }
        if (array_key_exists('VALUE',$arFields))
        {
            $object->setValue($arFields['VALUE']);
        }
        if (array_key_exists('CREATED_BY',$arFields))
        {
            $object->setCreatedBy($arFields['CREATED_BY']);
        }
        if (array_key_exists('CREATED_DATE',$arFields))
        {
            $object->setCreatedDate($arFields['CREATED_DATE']);
        }

        return $object;
    }

    public function __construct ()
    {

    }

    /**
     * @inheritDoc
     */
    public function getID ()
    {
        return $this->id;
    }

    /**
     * Устанавливает значение ID записи
     *
     * @param int $id ID записи
     *
     * @return $this
     */
    public function setID (int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getVariableName ()
    {
        return $this->variableName;
    }

    /**
     * Устанавливает имя переменной
     *
     * @param string $variableName
     *
     * @return $this
     */
    public function setVariableName (string $variableName)
    {
        $this->variableName = $variableName;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Устанавливает историческое значение переменной
     *
     * @param null|mixed $value Историческое значение переменной
     *
     * @return $this
     */
    public function setValue ($value = null)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedBy ()
    {
        return $this->createdBy;
    }

    /**
     * Устанавливае ID пользователя, добавившего запись
     *
     * @param int $createdBy ID пользователя
     *
     * @return $this
     */
    public function setCreatedBy (int $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getCreatedDate ()
    {
        return $this->createdDate;
    }

    /**
     * Устанавливает дату добавления
     *
     * @param Date|null $createdDate Дата добавления
     *
     * @return $this
     */
    public function setCreatedDate (Date $createdDate = null)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray (): array
    {
        $arFields = [];
        if (!is_null($this->id) && (int)$this->id > 0)
        {
            $arFields['ID'] = (int)$this->id;
        }
        if (!is_null($this->variableName) && strlen((string)$this->variableName) > 0)
        {
            $arFields['VARIABLE_NAME'] = (string)$this->variableName;
        }
        $arFields['VALUE'] = $this->value;
        if (!is_null($this->createdBy) && (int)$this->createdBy > 0)
        {
            $arFields['CREATED_BY'] = (int)$this->createdBy;
        }
        if (!is_null($this->createdDate) && $this->createdDate instanceof Date)
        {
            $arFields['CREATED_DATE'] = $this->createdDate;
        }

        return $arFields;
    }
}