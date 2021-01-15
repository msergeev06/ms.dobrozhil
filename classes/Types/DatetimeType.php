<?php
/**
 * Обработка свойств объектов типа datetime
 *
 * @package    Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Entity\Type\Date;

/**
 * Класс Ms\Dobrozhil\Types\DatetimeType
 * Тип значения "Дата/время"
 */
class DatetimeType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_S_DATETIME;
    }

    public static function getInstance (): DatetimeType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Дата/Время (S:DATETIME)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }

        try
        {
            return new Date($value, 'db_datetime');
        }
        catch (\Exception $e)
        {
            return null;
        }
    }

    public function processingValueToDB ($value = null): string
    {
        if (is_null($value))
        {
            return null;
        }
        elseif ($value instanceof Date)
        {
            return $value->getDateTimeDB();
        }
        else
        {
            return (string)$value;
        }
    }

    /**
     * Возвращает флаг возможности определять минимальное значение
     *
     * @return bool
     */
    public function canMin (): bool
    {
        return true;
    }

    /**
     * Возвращает флаг возможности определять максимальное значение
     *
     * @return bool
     */
    public function canMax (): bool
    {
        return true;
    }
}