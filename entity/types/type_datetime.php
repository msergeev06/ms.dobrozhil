<?php
/**
 * Обработка свойств объектов типа datetime
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Entity\Type\Date;

class TypeDatetime implements TypeProcessing
{
	protected static $instance = null;

	protected function __construct (){}

	public static function getInstance (): TypeProcessing
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function getTitle (): string
	{
		return 'S:Дата/Время';
	}

	public function getCode (): string
	{
		return 'S:DATETIME';
	}

	public function processingValueFromDB (string $value)
	{
		return new Date($value,'db_datetime');
	}

	public function processingValueToDB ($value): string
	{
		if ($value instanceof Date)
		{
			return $value->getDateTimeDB();
		}
		else
		{
			return (string)$value;
		}
	}
}