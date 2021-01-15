<?php
/**
 * @package    SHF "Доброжил"
 * @subpackage Ms\Core
 * @author     Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Dobrozhil\General\Multiton;
use Ms\Dobrozhil\Types\General\TypeInterface;

/**
 * Класс Ms\Dobrozhil\Types\TypeAbstract
 * Тип значения
 */
abstract class TypeAbstract extends Multiton implements TypeInterface
{
/*    protected static $instances = [];

    /**
     * Мультитон
     *
     * @return TypeInterface
     * /
    public static function getInstance ()
    {
        $className = get_called_class();
        if(!isset(static::$instances[$className]))
        {
            static::$instances[$className] = new $className();
        }

        return static::$instances[$className];
    }*/

    protected function __construct ()
    {
    }

    /**
     * Возвращает флаг возможности складывать значения
     *
     * @return bool
     */
    public function canSum (): bool
    {
        return false;
    }

    /**
     * Возвращает флаг возможности высчитывать среднее значение
     *
     * @return bool
     */
    public function canAvg (): bool
    {
        return false;
    }

    /**
     * Возвращает флаг возможности определять минимальное значение
     *
     * @return bool
     */
    public function canMin (): bool
    {
        return false;
    }

    /**
     * Возвращает флаг возможности определять максимальное значение
     *
     * @return bool
     */
    public function canMax (): bool
    {
        return false;
    }
}