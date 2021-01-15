<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Dobrozhil
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2021 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Variables\General;

use Ms\Core\Entity\Db\Result\DBResult;
use Ms\Core\Entity\Db\Tables\ORMController;
use Ms\Core\Entity\System\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Exceptions\Db\SqlQueryException;
use Ms\Dobrozhil\Tables\VariablesHistoryTable;

/**
 * Класс Ms\Dobrozhil\Variables\General\HistoryDbHelper
 * Помощник работы с таблицей исторических значений переменной
 */
class HistoryDbHelper
{
    /** @var HistoryDbHelper[] */
    protected static $instances = [];
    /** @var ORMController */
    protected $orm = null;

    public static function getInstance (string $additionalName)
    {
        if (!isset(static::$instances[$additionalName]))
        {
            static::$instances[$additionalName] = new static ($additionalName);
        }

        return static::$instances[$additionalName];
    }

    protected function __construct (string $additionalName)
    {
        $this->orm = ORMController::getInstance(new VariablesHistoryTable($additionalName));
    }

    /**
     * Возвращает массив с полем CREATED_DATE, содержащий дату первой записи исторических значений, либо FALSE
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function getFirstHistoryDateTime ()
    {
        return $this->orm->getOne(
            [
                'select' => ['CREATED_DATE'],
                'order'  => ['CREATED_DATE' => 'ASC']
            ]
        );
    }

    /**
     * Возвращает массив с полем CREATED_DATE, содержащий дату последней записи исторических значений, либо FALSE
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function getLastHistoryDateTime ()
    {
        return $this->orm->getOne(
            [
                'select' => ['CREATED_DATE'],
                'order'  => ['CREATED_DATE' => 'DESC']
            ]
        );
    }

    /**
     * Возвращает минимальное историческое значение переменной, либо FALSE
     *
     * @param Date|null $startDate Начальная дата, может быть опущена
     * @param Date|null $stopDate  Конечная дата, может быть опущена
     * @param array     $arSelect  Список возвращаемых полей (по умолчанию пустой массив - возвращать все поля)
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function getHistoryMin (Date $startDate = null, Date $stopDate = null, array $arSelect = [])
    {
        $arFilter = [];
        if (!is_null($startDate))
        {
            $arFilter['>=CREATED_DATE'] = $startDate;
        }
        if (!is_null($stopDate))
        {
            $arFilter['<=CREATED_DATE'] = $stopDate;
        }

        return $this->orm->getOne(
            [
                'select' => $arSelect,
                'filter' => $arFilter,
                'order'  => ['VALUE' => 'ASC']
            ]
        );
    }

    /**
     * Возвращает максимальное историческое значение переменной, либо FALSE
     *
     * @param Date|null $startDate Начальная дата, может быть опущена
     * @param Date|null $stopDate  Конечная дата, может быть опущена
     * @param array     $arSelect  Список возвращаемых полей (по умолчанию пустой массив - возвращать все поля)
     *
     * @return array|bool|string
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     * @throws \Ms\Core\Exceptions\Db\SqlQueryException
     */
    public function getHistoryMax (Date $startDate = null, Date $stopDate = null, array $arSelect = [])
    {
        $arFilter = [];
        if (!is_null($startDate))
        {
            $arFilter['>=CREATED_DATE'] = $startDate;
        }
        if (!is_null($stopDate))
        {
            $arFilter['<=CREATED_DATE'] = $stopDate;
        }

        return $this->orm->getOne(
            [
                'select' => $arSelect,
                'filter' => $arFilter,
                'order'  => ['VALUE' => 'DESC']
            ]
        );
    }

    /**
     * Возвращает объект результата запросса суммы исторических значений за указанный период
     *
     * @param Date|null $startDate Начальная дата периода, может быть опущена
     * @param Date|null $stopDate  Конечная дата периода, может быть опущена
     *
     * @return DBResult
     * @throws SqlQueryException
     */
    public function getHistorySum (Date $startDate = null, Date $stopDate = null): DBResult
    {
        $sql = <<<EOL
SELECT
    SUM (VALUE) as SUMM
FROM 
    #TABLE_NAME#
WHERE
    #SQL_WHERE#    
EOL;
        $sql = str_replace('#TABLE_NAME#', $this->orm->getTableName(), $sql);
        $sqlWhere = $this->getSqlWhereDates($startDate, $stopDate);
        $sql = str_replace('#SQL_WHERE#', $sqlWhere, $sql);

        $conn = Application::getInstance()->getConnection();

        return $conn->querySQL($sql);
    }

    /**
     * Возвращает объект результата запроса количества исторических значений за указанный период
     *
     * @param Date|null $startDate Начальная дата периода, может быть опущена
     * @param Date|null $stopDate  Конечная дата периода, может быть опущена
     *
     * @return DBResult
     * @throws SqlQueryException
     */
    public function getHistoryCount (Date $startDate = null, Date $stopDate = null): DBResult
    {
        $sql = <<<EOL
SELECT
    COUNT (VALUE) as CNT
FROM
    #TABLE_NAME#
WHERE
    #SQL_WHERE#
EOL;
        $sql = str_replace('#TABLE_NAME#', $this->orm->getTableName(), $sql);
        $sqlWhere = $this->getSqlWhereDates($startDate, $stopDate);
        $sql = str_replace('#SQL_WHERE#', $sqlWhere, $sql);

        $conn = Application::getInstance()->getConnection();

        return $conn->querySQL($sql);
    }

    /**
     * Возвращает объект результата запроса среднего значения исторических значений за указанный период
     *
     * @param Date|null $startDate Начальная дата периода, может быть опущена
     * @param Date|null $stopDate  Конечная дата периода, может быть опущена
     *
     * @return DBResult
     * @throws SqlQueryException
     */
    public function getHistoryAvg (Date $startDate = null, Date $stopDate = null): DBResult
    {
        $sql = <<<EOL
SELECT
    AVG (VALUE) as AVG
FROM
    #TABLE_NAME#
WHERE
    #SQL_WHERE#
EOL;
        $sql = str_replace('#TABLE_NAME#', $this->orm->getTableName(), $sql);
        $sqlWhere = $this->getSqlWhereDates($startDate, $stopDate);
        $sql = str_replace('#SQL_WHERE#',$sqlWhere, $sql);

        $conn = Application::getInstance()->getConnection();

        return $conn->querySQL($sql);
    }

    /**
     * Возвращает список записей исторических значений переменной за указанный период
     *
     * @param Date|null $startDate Начальная дата периода, может быть опущена
     * @param Date|null $stopDate  Конечная дата периода, может быть опущена
     * @param array     $arSelect  Список возращаемых полей (по умолчанию пустой массив - возвращать все поля)
     *
     * @return array|bool|string
     * @throws SqlQueryException
     * @throws \Ms\Core\Exceptions\Arguments\ArgumentTypeException
     */
    public function getHistoryList (Date $startDate = null, Date $stopDate = null, array $arSelect = [])
    {
        $arFilter = [];
        if (!is_null($startDate))
        {
            $arFilter['>=CREATED_DATE'] = $startDate;
        }
        if (!is_null($stopDate))
        {
            $arFilter['<=CREATED_DATE'] = $stopDate;
        }

        return $this->orm->getList(
            [
                'select' => $arSelect,
                'filter' => $arFilter,
                'order' => ['CREATED_DATE' => 'ASC']
            ]
        );
    }

    /**
     * Возвращает параметры запроса SQL WHERE для переданных дат
     *
     * @param Date|null $startDate Начальная дата периода, может быть опущена
     * @param Date|null $stopDate  Конечная дата периода, может быть опущена
     *
     * @return string
     */
    protected function getSqlWhereDates (Date $startDate = null, Date $stopDate = null): string
    {
        $sqlWhere = "";
        if (!is_null($startDate))
        {
            $sqlWhere .= 'CREATED_DATE >= "' . $startDate->getDateTimeDB() . '"';
            if (!is_null($stopDate))
            {
                $sqlWhere .= ' AND ';
            }
        }
        if (!is_null($stopDate))
        {
            $sqlWhere .= 'CREATED_DATE <= "' . $stopDate->getDateTimeDB() . '"';
        }

        return $sqlWhere;
    }
}