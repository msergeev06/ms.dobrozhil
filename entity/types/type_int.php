<?php
/**
 * Обработка свойств объектов типа int
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Lib\Tools;

class TypeInt implements TypeProcessing
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
		return 'N:Целое число';
	}

	public function getCode (): string
	{
		return 'N:INT';
	}

	public function processingValueFromDB (string $value)
	{
		return Tools::validateIntVal($value);
	}

	public function processingValueToDB ($value): string
	{
		return (string)Tools::validateIntVal($value);
	}
}