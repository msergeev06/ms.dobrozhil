<?php
/**
 * Обработка свойств объектов типа float
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Lib\Tools;

class TypeFloat implements TypeProcessing
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
		return 'Число (N)';
	}

	public function getCode (): string
	{
		return 'N';
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
}