<?php
/**
 * Обработка свойств объектов типа time
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
 * Класс Ms\Dobrozhil\Types\TimeType
 * Тип значения "Время"
 */
class TimeType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_S_TIME;
    }

    public static function getInstance (): TimeType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Время (S:TIME)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }

        try
        {
            return new Date($value, 'site_time');
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
            return $value->getTimeSite();
        }
        else
        {
            return (string)$value;
        }
    }
}