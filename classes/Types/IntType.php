<?php
/**
 * Обработка свойств объектов типа int
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

use Ms\Core\Lib\Tools;

/**
 * Класс Ms\Dobrozhil\Types\IntType
 * Тип значения "Целое число"
 */
class IntType extends TypeAbstract implements General\TypeInterface
{
	public static function getInstance (): IntType
	{
		return parent::getInstance();
	}

	public function getTitle (): string
	{
		return 'Целое число (N:INT)';
	}

	public function getCode (): string
	{
		return General\Constants::TYPE_N_INT;
	}

	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}

		return Tools::validateIntVal($value);
	}

	public function processingValueToDB ($value=null): string
	{
		if (is_null($value))
		{
			return NULL;
		}

		return (string)Tools::validateIntVal($value);
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