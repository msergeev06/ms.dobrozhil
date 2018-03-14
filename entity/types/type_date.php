<?php
/**
 * Обработка свойств объектов типа date
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Entity\Type\Date;

class TypeDate implements TypeProcessing
{
	protected static $instance = null;

	protected function __construct (){}

	public static function getInstance ()
	{
		if (is_null(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}

	public function processingValueFromDB (string $value)
	{
		return new Date($value);
	}

	public function processingValueToDB ($value): string
	{
		if ($value instanceof Date)
		{
			return $value->getDateDB();
		}
		else
		{
			return (string)$value;
		}
	}
}