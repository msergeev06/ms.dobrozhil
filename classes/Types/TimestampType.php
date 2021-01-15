<?php
/**
 * Обработка свойств объектов типа timestamp
 *
 * @package    Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Entity\Type\Date;
use Ms\Core\Exceptions\SystemException;

/**
 * Класс Ms\Dobrozhil\Types\TimestampType
 * Тип значения "Метка времени UNIX"
 */
class TimestampType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_N_TIMESTAMP;
    }

    public static function getInstance (): TimestampType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Метка времени UNIX (N:TIMESTAMP)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }

        try
        {
            return new Date($value, 'time');
        }
        catch (SystemException $e)
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
            return (string)$value->getTimestamp();
        }
        else
        {
            return (string)(int)$value;
        }
    }

    /**
     * Возвращает флаг возможности складывать значения
     *
     * @return bool
     */
    public function canSum (): bool
    {
        return true;
    }

    /**
     * Возвращает флаг возможности высчитывать среднее значение
     *
     * @return bool
     */
    public function canAvg (): bool
    {
        return true;
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