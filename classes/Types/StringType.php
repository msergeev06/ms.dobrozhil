<?php
/**
 * Обработка свойств объектов типа string
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 * @copyright  2020 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Types;

/**
 * Класс Ms\Dobrozhil\Types\StringType
 * Тип значения "Строка"
 */
class StringType extends TypeAbstract implements General\TypeInterface
{
    public static function getInstance (): StringType
    {
        return parent::getInstance();
    }

    public function getTitle (): string
	{
		return 'Строка (S)';
	}

	public function getCode (): string
	{
		return General\Constants::TYPE_STRING;
	}

	public function processingValueFromDB (string $value=NULL)
	{
		if (is_null($value))
		{
			return NULL;
		}

		return (string)$value;
	}

	public function processingValueToDB ($value=null): string
	{
		if (is_null($value))
		{
			return NULL;
		}

		return (string)$value;
	}
}