<?php
/**
 * Обработка свойств объектов типа timestamp
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Entity\Type\Date;

class TypeTimestamp implements TypeProcessing
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
		return 'N:Метка времени UNIX';
	}

	public function getCode (): string
	{
		return 'N:TIMESTAMP';
	}

	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}
		return new Date($value,'time');
	}

	public function processingValueToDB ($value=null): string
	{
		if (is_null($value))
		{
			return NULL;
		}
		elseif ($value instanceof Date)
		{
			return (string)$value->getTimestamp();
		}
		else
		{
			return (string)(int)$value;
		}
	}
}