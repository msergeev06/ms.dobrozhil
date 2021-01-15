<?php
/**
 * Обработка свойств объектов типа float
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
 * Класс Ms\Dobrozhil\Types\FloatType
 * Тип значения "Число"
 */
class FloatType extends TypeAbstract implements General\TypeInterface
{
    public static function getInstance (): FloatType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
	{
		return 'Число (N)';
	}

	public function getCode (): string
	{
		return General\Constants::TYPE_NUMERIC;
	}

	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}

		return Tools::validateFloatVal($value);
	}

	public function processingValueToDB ($value=NULL): string
	{
		if (is_null($value))
		{
			return NULL;
		}

		return (string)Tools::validateFloatVal($value);
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