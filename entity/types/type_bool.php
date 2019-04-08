<?php
/**
 * Обработка свойств объектов типа bool
 *
 * @package Ms\Dobrozhil
 * @subpackage Entity\Types
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Entity\Types;

use Ms\Dobrozhil\Interfaces\TypeProcessing;
use Ms\Core\Lib\Tools;

class TypeBool implements TypeProcessing
{
	/**
	 * @var TypeProcessing
	 */
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

	public function getTitle ():string
	{
		return 'Да/Нет (B)';
	}

	public function getCode (): string
	{
		return 'B';
	}

	public function processingValueFromDB (string $value=null)
	{
		if (is_null($value))
		{
			return NULL;
		}
		else
		{
			return Tools::validateBoolVal($value);
		}
	}

	public function processingValueToDB ($value=null): string
	{
		if (is_null($value))
		{
			return NULL;
		}
		else
		{
			return ($value)?'Y':'N';
		}
	}
}