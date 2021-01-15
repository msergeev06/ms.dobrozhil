<?php
/**
 * Обработка свойств объектов типа coordinates
 *
 * @package    Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Dobrozhil\Type\GpsCoordinates;

/**
 * Класс Ms\Dobrozhil\Types\CoordinatesType
 * Тип значения "Координаты GPS"
 */
class CoordinatesType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_S_COORDINATES;
    }

    public static function getInstance (): CoordinatesType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Координаты (S:COORDINATES)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }

        return (new GpsCoordinates())
            ->setFromString($value)
        ;
    }

    /**
     * @param null|GpsCoordinates $value
     *
     * @return string
     */
    public function processingValueToDB ($value = null): string
    {
        if (is_null($value) || !($value instanceof GpsCoordinates))
        {
            return null;
        }

        return $value->__toString();
    }
}