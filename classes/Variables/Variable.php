<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables;

use Ms\Core\Entity\Type\Date;
use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\Types\General\Constants;
use Ms\Dobrozhil\Types\General\TypeInterface;
use Ms\Dobrozhil\Types\TypeBuilder;
use Ms\Dobrozhil\Variables\General\VariableInterface;

/**
 * Класс Ms\Dobrozhil\Variables\Variable
 * Параметры виртуальной переменной умного дома
 */
class Variable implements VariableInterface
{
    /** @var null|string */
    private $name                = null;
    /** @var null|string */
    private $title               = null;
    /** @var string|null */
    private $type                = null;
    /** @var TypeInterface|null  */
    private $typeController      = null;
    /** @var null|string */
    private $rawValue            = null;
    /** @var null|mixed */
    private $value               = null;
    /** @var null|bool */
    private $hidden              = null;
    /** @var null|int */
    private $historyDays         = null;
    /** @var null|string */
    private $historyTablePostfix = null;
    /** @var null|array */
    private $optimizationParams  = null;
    /** @var null|bool */
    private $saveIdenticalValues = null;
    /** @var null|bool */
    private $automated           = null;
    /** @var null|bool */
    private $readOnly            = null;
    /** @var null|int */
    private $createdBy           = null;
    /** @var null|Date */
    private $createdDate         = null;
    /** @var null|int */
    private $updatedBy           = null;
    /** @var null|Date */
    private $updatedDate         = null;

    public function __construct ()
    {
        $this->type = Constants::TYPE_STRING;
        $this->typeController = TypeBuilder::getInstance()->create(Constants::TYPE_STRING);
    }

    /**
     * @inheritDoc
     */
    final public function getName ()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    final public function setName (string $name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getTitle ()
    {
        return $this->title;
    }

    /**
     * @inheritDoc
     */
    final public function setTitle (string $title = null)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getTypeCode (): string
    {
        return $this->type;
    }

    /**
     * @inheritDoc
     */
    final public function getTypeController (): TypeInterface
    {
        return $this->typeController;
    }

    /**
     * @inheritDoc
     */
    final public function setType (string $typeCode = Constants::TYPE_STRING)
    {
        $typeCode = strtoupper($typeCode);
        if (TypeBuilder::getInstance()->isRightTypeCode($typeCode))
        {
            $this->type = $typeCode;
            $this->typeController = TypeBuilder::getInstance()->create($typeCode);
        }

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
     * @inheritDoc
     * @throws SystemException
     */
    public function setValue ($value = null)
    {
        if (!is_null($this->typeController))
        {
            $this->value = $value;
            $this->rawValue = $this->typeController->processingValueToDB($value);
        }
        else
        {
            throw new SystemException('Не установлен обработчик для заданного типа переменной');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRawValue (): string
    {
        return $this->rawValue;
    }

    /**
     * @inheritDoc
     * @throws SystemException
     */
    public function setRawValue (string $rawValue): VariableInterface
    {
        if (!is_null($this->typeController))
        {
            $this->rawValue = $rawValue;
            $this->value = $this->typeController->processingValueFromDB($rawValue);
        }
        else
        {
            throw new SystemException('Не установлен обработчик для заданного типа переменной');
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function isHidden (): bool
    {
        return ($this->hidden === true) ? true : false;
    }

    /**
     * @inheritDoc
     */
    final public function setHidden (bool $isHidden = false)
    {
        $this->hidden = (bool)$isHidden;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getHistoryDays (): int
    {
        return (is_null($this->historyDays)) ? 0 : (int)$this->historyDays;
    }

    /**
     * @inheritDoc
     */
    final public function setHistoryDays (int $numberOfDays = 0)
    {
        if ((int)$numberOfDays >= 0)
        {
            $this->historyDays = (int)$numberOfDays;
        }
        else
        {
            $this->historyDays = 0;
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getHistoryTableName ()
    {
        return $this->historyTablePostfix;
    }

    /**
     * @inheritDoc
     */
    final public function setHistoryTableName (string $historyTableName = null)
    {
        $this->historyTablePostfix = $historyTableName;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getOptimizationHistoryParams ()
    {
        return $this->optimizationParams;
    }

    /**
     * @inheritDoc
     */
    public function setOptimizationHistoryParams (array $arParams = null)
    {
        $this->optimizationParams = $arParams;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function isSaveIdenticalValues (): bool
    {
        return ($this->saveIdenticalValues === true) ? true : false;
    }

    /**
     * @inheritDoc
     */
    final public function setSaveIdenticalValues (bool $isSaveIdenticalValues = true)
    {
        $this->saveIdenticalValues = (bool)$isSaveIdenticalValues;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function isAutomated (): bool
    {
        return ($this->automated === true) ? true : false;
    }

    /**
     * @inheritDoc
     */
    final public function setAutomated (bool $isAutomated = false)
    {
        $this->automated = (bool)$isAutomated;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function isReadOnly (): bool
    {
        return ($this->readOnly === true) ? true : false;
    }

    /**
     * @inheritDoc
     */
    public function setReadOnly (bool $isReadOnly = false)
    {
        $this->readOnly = (bool) $isReadOnly;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getCreatedBy ()
    {
        return $this->createdBy;
    }

    /**
     * @inheritDoc
     */
    final public function setCreatedBy (int $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getCreatedDate ()
    {
        return $this->createdDate;
    }

    /**
     * @inheritDoc
     */
    final public function setCreatedDate (Date $createdDate = null)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getUpdatedBy ()
    {
        return $this->updatedBy;
    }

    /**
     * @inheritDoc
     */
    final public function setUpdatedBy (int $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * @inheritDoc
     */
    final public function getUpdatedDate ()
    {
        return $this->updatedDate;
    }

    /**
     * @inheritDoc
     */
    final public function setUpdatedDate (Date $updatedDate = null)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function toArray (): array
    {
        $arFields = [];
        if (!is_null($this->name))
        {
            $arFields['NAME'] = $this->name;
        }
        if (!is_null($this->title))
        {
            $arFields['TITLE'] = $this->title;
        }
        if (!is_null($this->type))
        {
            $arFields['TYPE'] = $this->type;
        }
        if (!is_null($this->rawValue))
        {
            $arFields['VALUE'] = $this->rawValue;
        }
        if (!is_null($this->hidden))
        {
            $arFields['HIDDEN'] = $this->isHidden();
        }
        if (!is_null($this->historyDays))
        {
            $arFields['HISTORY_DAYS'] = $this->getHistoryDays();
            if ((int)$arFields['HISTORY_DAYS'] > 0 && !is_null($this->historyTablePostfix))
            {
                $arFields['HISTORY_TABLE_NAME'] = $this->historyTablePostfix;
            }
        }
        if (!is_null($this->optimizationParams))
        {
            $arFields['OPTIMIZE_HISTORY_PARAMS'] = $this->optimizationParams;
        }
        if (!is_null($this->saveIdenticalValues))
        {
            $arFields['SAVE_IDENTICAL_VALUES'] = $this->isSaveIdenticalValues();
        }
        if (!is_null($this->automated))
        {
            $arFields['AUTOMATED'] = $this->isAutomated();
        }
        if (!is_null($this->readOnly))
        {
            $arFields['READONLY'] = $this->isReadOnly();
        }
        if (!is_null($this->createdBy) && (int)$this->createdBy > 0)
        {
            $arFields['CREATED_BY'] = (int)$this->createdBy;
        }
        if (!is_null($this->createdDate) && $this->createdDate instanceof Date)
        {
            $arFields['CREATED_DATE'] = $this->createdDate;
        }
        if (!is_null($this->updatedBy) && (int)$this->updatedBy > 0)
        {
            $arFields['UPDATED_BY'] = (int)$this->updatedBy;
        }
        if (!is_null($this->updatedDate) && $this->updatedDate instanceof Date)
        {
            $arFields['UPDATED_DATE'] = $this->updatedDate;
        }

        return $arFields;
    }

    /**
     * @inheritDoc
     */
    public static function createFromArray (array $arDbFields): VariableInterface
    {
        $obj = new static();
        if (array_key_exists('NAME',$arDbFields))
        {
            $obj->setName($arDbFields['NAME']);
        }
        else
        {
            return $obj;
        }
        if (array_key_exists('TITLE',$arDbFields))
        {
            $obj->setTitle($arDbFields['TITLE']);
        }
        if (array_key_exists('TYPE',$arDbFields))
        {
            $obj->setType($arDbFields['TYPE']);
        }
        if (array_key_exists('VALUE',$arDbFields))
        {
            $obj->setRawValue($arDbFields['VALUE']);
        }
        if (array_key_exists('HIDDEN',$arDbFields))
        {
            if (!is_null($arDbFields['HIDDEN']) && is_bool($arDbFields['HIDDEN']))
            {
                $obj->setHidden((bool)$arDbFields['HIDDEN']);
            }
        }
        if (array_key_exists('HISTORY_DAYS',$arDbFields))
        {
            $obj->setHistoryDays((int)$arDbFields['HISTORY_DAYS']);
            if ((int)$arDbFields['HISTORY_DAYS'] > 0)
            {
                if (array_key_exists('HISTORY_TABLE_NAME',$arDbFields))
                {
                    $obj->setHistoryTableName($arDbFields['HISTORY_TABLE_NAME']);
                }
            }
        }
        if (array_key_exists('OPTIMIZE_HISTORY_PARAMS',$arDbFields) && !is_null($arDbFields['OPTIMIZE_HISTORY_PARAMS']))
        {
            $obj->setOptimizationHistoryParams($arDbFields['OPTIMIZE_HISTORY_PARAMS']);
        }
        if (array_key_exists('SAVE_IDENTICAL_VALUES',$arDbFields))
        {
            if (!is_null($arDbFields['SAVE_IDENTICAL_VALUES']) && is_bool($arDbFields['SAVE_IDENTICAL_VALUES']))
            {
                $obj->setSaveIdenticalValues((bool)$arDbFields['SAVE_IDENTICAL_VALUES']);
            }
        }
        if (array_key_exists('AUTOMATED',$arDbFields))
        {
            if (!is_null($arDbFields['AUTOMATED']) && is_bool($arDbFields['AUTOMATED']))
            {
                $obj->setAutomated((bool)$arDbFields['AUTOMATED']);
            }
        }
        if (array_key_exists('READONLY',$arDbFields))
        {
            if (!is_null($arDbFields['READONLY']) && is_bool($arDbFields['READONLY']))
            {
                $obj->setReadOnly((bool)$arDbFields['READONLY']);
            }
        }
        if (array_key_exists('CREATED_BY',$arDbFields))
        {
            if (!is_null($arDbFields['CREATED_BY']) && (int)$arDbFields['CREATED_BY'] > 0)
            {
                $obj->setCreatedBy($arDbFields['CREATED_BY']);
            }
        }
        if (array_key_exists('CREATED_DATE',$arDbFields))
        {
            if (!is_null($arDbFields['CREATED_DATE']) && $arDbFields['CREATED_DATE'] instanceof Date)
            {
                $obj->setCreatedDate($arDbFields['CREATED_DATE']);
            }
        }
        if (array_key_exists('UPDATED_BY',$arDbFields))
        {
            if (!is_null($arDbFields['UPDATED_BY']) && (int)$arDbFields['UPDATED_BY'] > 0)
            {
                $obj->setUpdatedBy($arDbFields['UPDATED_BY']);
            }
        }
        if (array_key_exists('UPDATED_DATE',$arDbFields))
        {
            if (!is_null($arDbFields['UPDATED_DATE']) && $arDbFields['UPDATED_DATE'] instanceof Date)
            {
                $obj->setUpdatedDate($arDbFields['UPDATED_DATE']);
            }
        }

        return $obj;
    }
}