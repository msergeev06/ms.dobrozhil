<?php
/**
 * Обработка свойств объектов типа coordinates
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;

class TypeCoordinates implements TypeProcessing
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
		return 'S:Координаты';
	}

	public function getCode (): string
	{
		return 'S:COORDINATES';
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