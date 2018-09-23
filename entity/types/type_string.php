<?php
/**
 * Обработка свойств объектов типа string
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;

class TypeString implements TypeProcessing
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
		return 'Строка (S)';
	}

	public function getCode (): string
	{
		return 'S';
	}

	public function processingValueFromDB (string $value)
	{
		return (string)$value;
	}

	public function processingValueToDB ($value): string
	{
		return (string)$value;
	}
}