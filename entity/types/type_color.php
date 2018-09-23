<?php
/**
 * Обработка свойств объектов типа color
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;

class TypeColor implements TypeProcessing
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
		return 'S:Цвет';
	}

	public function getCode (): string
	{
		return 'S:COLOR';
	}

	public function processingValueFromDB (string $value)
	{
		return (string)$value;
	}

	public function processingValueToDB ($value): string
	{
		if (!strpos($value,'#')===false)
		{
			$value = '#'.$value;
		}
		if (preg_match('/#[0-9A-F]{6}/',$value))
		{
			return (string)$value;
		}
		else
		{
			return '#000000';
		}
	}
}