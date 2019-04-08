<?php
/**
 * Обработка свойств объектов типа time
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Entity\Type\Date;

class TypeTime implements TypeProcessing
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
		return 'S:Время';
	}

	public function getCode (): string
	{
		return 'S:TIME';
	}

	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}
		return new Date($value,'site_time');
	}

	public function processingValueToDB ($value=null): string
	{
		if (is_null($value))
		{
			return NULL;
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