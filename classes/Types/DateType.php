<?php
/**
 * Обработка свойств объектов типа date
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
 * Класс Ms\Dobrozhil\Types\DateType
 * Тип значения "Дата"
 */
class DateType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_S_DATE;
    }

    public static function getInstance (): DateType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Дата (S:DATE)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }

        try
        {
            return new Date($value);
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
            return $value->getDateDB();
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