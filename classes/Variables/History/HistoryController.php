<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\History;

use Ms\Core\Entity\Errors\FileLogger;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Exceptions\Db\SqlQueryException;
use Ms\Core\Exceptions\SystemException;
use Ms\Dobrozhil\Variables\General\HistoryControllerInterface;
use Ms\Dobrozhil\Variables\General\HistoryDbHelper;
use Ms\Dobrozhil\Variables\General\VariableInterface;

/**
 * Класс Ms\Dobrozhil\Variables\History\HistoryController
 * Контроллер для исторических значений переменной
 */
class HistoryController implements HistoryControllerInterface
{
    /** @var HistoryControllerInterface[] */
    protected static $instances = [];
    /** @var VariableInterface */
    protected $variable = null;
    /** @var FileLogger */
    protected $logger = null;

    /**
     * @inheritDoc
     */
    public static function getInstance (VariableInterface $variable)
    {
        $additionalName = $variable->getHistoryTableName();
        if (is_null($additionalName))
        {
            return null;
        }

        if (!isset(static::$instances[$additionalName]))
        {
            static::$instances[$additionalName] = new static ($variable);
        }

        return static::$instances[$additionalName];
    }

    protected function __construct (VariableInterface $variable)
    {
        $this->setVariable($variable);
        $this->logger = new FileLogger('ms.dobrozhil');
    }

    /**
     * @inheritDoc
     */
    public function setVariable (VariableInterface $variable)
    {
        $this->variable = $variable;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getFirstHistoryDateTime ()
    {
        try
        {
            $arRes = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())->getFirstHistoryDateTime();
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return null;
        }
        if (
            $arRes === false
            || !array_key_exists('CREATED_DATE',$arRes)
            || is_null($arRes['CREATED_DATE'])
            || !($arRes['CREATED_DATE'] instanceof Date)
        ) {
            return null;
        }

        return $arRes['CREATED_DATE'];
    }

    /**
     * @inheritDoc
     */
    public function getLastHistoryDateTime ()
    {
        try
        {
            $arRes = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())->getFirstHistoryDateTime();
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return null;
        }
        if (
            $arRes === false
            || !array_key_exists('CREATED_DATE',$arRes)
            || is_null($arRes['CREATED_DATE'])
            || !($arRes['CREATED_DATE'] instanceof Date)
        ) {
            return null;
        }

        return $arRes['CREATED_DATE'];
    }

    /**
     * @inheritDoc
     */
    public function getHistoryMin (Date $startDate = null, Date $stopDate = null)
    {
        try
        {
            $arRes = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                    ->getHistoryMin($startDate, $stopDate)
            ;
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return null;
        }
        if ($arRes === false)
        {
            return null;
        }

        return HistoryValue::createFromArray($arRes);
    }

    /**
     * @inheritDoc
     */
    public function getHistoryMax (Date $startDate = null, Date $stopDate = null)
    {
        try
        {
            $arRes = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                    ->getHistoryMax($startDate, $stopDate)
            ;
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return null;
        }
        if ($arRes === false)
        {
            return null;
        }

        return HistoryValue::createFromArray($arRes);
    }

    /**
     * @inheritDoc
     */
    public function getHistorySum (Date $startDate = null, Date $stopDate = null): float
    {
        try
        {
            $res = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                  ->getHistorySum($startDate, $stopDate)
            ;
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog($this->logger);

            return 0;
        }
        if ($res->isSuccess())
        {
            if ($arRes = $res->fetch())
            {
                if (array_key_exists('SUMM',$arRes))
                {
                    return (float)$arRes['SUMM'];
                }
            }
        }

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getHistoryCount (Date $startDate = null, Date $stopDate = null): int
    {
        try
        {
            $res = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                  ->getHistoryCount($startDate, $stopDate)
            ;
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog($this->logger);

            return 0;
        }
        if ($res->isSuccess())
        {
            if ($arRes = $res->fetch())
            {
                if (array_key_exists('CNT',$arRes))
                {
                    return (int)$arRes['CNT'];
                }
            }
        }

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getHistoryAvg (Date $startDate = null, Date $stopDate = null): float
    {
        try
        {
            $res = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                  ->getHistoryAvg($startDate, $stopDate)
            ;
        }
        catch (SqlQueryException $e)
        {
            $e->addMessageToLog($this->logger);

            return 0;
        }
        if ($res->isSuccess())
        {
            if ($arRes = $res->fetch())
            {
                if (array_key_exists('AVG',$arRes))
                {
                    return (float)$arRes['AVG'];
                }
            }
        }

        return 0;
    }

    /**
     * @inheritDoc
     */
    public function getHistoryCollection (Date $startDate = null, Date $stopDate = null): HistoryValueCollection
    {
        $collection = new HistoryValueCollection();

        try
        {
            $arRes = HistoryDbHelper::getInstance($this->variable->getHistoryTableName())
                                    ->getHistoryList($startDate, $stopDate)
            ;
        }
        catch (SystemException $e)
        {
            $e->addMessageToLog($this->logger);

            return $collection;
        }
        if (!empty($arRes))
        {
            foreach ($arRes as $arHistoryValue)
            {
                $collection
                    ->addHistoryValue(
                        HistoryValue::createFromArray($arHistoryValue)
                    )
                ;
            }
        }

        return $collection;
    }
}