<?php
/**
 * Обработка свойств объектов типа color
 *
 * @package    Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Entity\Type\Color;

/**
 * Класс Ms\Dobrozhil\Entity\Types\ColorType
 * Тип значения "Цвет"
 */
class ColorType extends TypeAbstract implements General\TypeInterface
{
    public function getCode (): string
    {
        return General\Constants::TYPE_S_COLOR;
    }

    public static function getInstance (): ColorType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
    {
        return 'Цвет (S:COLOR)';
    }

    public function processingValueFromDB (string $value = null)
    {
        if (is_null($value))
        {
            return null;
        }
        else
        {
            return new Color((string)$value);
        }
    }

    public function processingValueToDB ($value = null): string
    {
        if (is_null($value))
        {
            return null;
        }
        if ($value instanceof Color)
        {
            return $value->getFormatHexString();
        }
        if (!strpos($value, '#') === false)
        {
            $value = '#' . $value;
        }
        if (preg_match('/#[0-9A-F]{6}/', $value))
        {
            return (string)$value;
        }
        else
        {
            return '#000000';
        }
    }
}