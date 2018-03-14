<?php
/**
 * Обработка типов свойств объектов
 *
 * @package Ms\Dobrozhil
 * @subpackage Lib
 * @author Mikhail Sergeev <msergeev06@gmail.com>
 * @copyright 2018 Mikhail Sergeev
 */

namespace Ms\Dobrozhil\Lib;

use Ms\Core\Entity\Application;
use Ms\Core\Entity\Type\Date;
use Ms\Core\Lib\Loader;
use Ms\Core\Lib\Tools;
use Ms\Dobrozhil\Interfaces\TypeProcessing;

class Types
{
	/**
	 * Обрабатывает значение свойства заданного типа после получения из БД
	 *
	 * @param mixed  $mValue Значение свойства
	 * @param string $sType  Тип свойства
	 *
	 * @return mixed
	 */
	public static function prepareValueFrom ($mValue, $sType='string')
	{
		if (strtolower($sType) == 'string')
		{
			/** @var TypeProcessing $handler */
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
			return $handler->processingValueFromDB($mValue);
		}
		/** @var TypeProcessing $handler */
		$handler = static::getHandler($sType);
		if ($handler && $handler instanceof TypeProcessing)
		{
			return $handler->processingValueFromDB($mValue);
		}
		else
		{
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
			return $handler->processingValueFromDB($mValue);
		}
	}

	/**
	 * Обрабатывает значение свойства заданного типа перед сохранением в БД
	 *
	 * @param mixed  $mValue Значение свойства
	 * @param string $sType  Тип свойства
	 *
	 * @return mixed
	 */
	public static function prepareValueTo ($mValue, $sType='string')
	{
		if (strtolower($sType) == 'string')
		{
			/** @var TypeProcessing $handler */
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
			return $handler->processingValueToDB($mValue);
		}
		/** @var TypeProcessing $handler */
		$handler = static::getHandler($sType);
		if ($handler && $handler instanceof TypeProcessing)
		{
			return $handler->processingValueToDB($mValue);
		}
		else
		{
			$handler = call_user_func('Ms\Dobrozhil\Entity\Types\TypeString::getInstance');
			return $handler->processingValueToDB($mValue);
		}
	}

	/**
	 * Определяет и возвращает обработчик значения свойства, либо false
	 *
	 * @param string $sType  Тип свойства
	 *
	 * @return string|bool
	 */
	protected static function getHandler ($sType='string')
	{
		$sType = strtolower($sType);
		$fileName = Application::getInstance()->getSettings()->getModulesRoot()
			.'/ms.dobrozhil/include/types/type_'.$sType.'.php';
		if (!file_exists($fileName))
		{
			return false;
		}
		else
		{
			$typeHandler = include($fileName);
			if (!$typeHandler)
			{
				return false;
			}
			else
			{
				return call_user_func($typeHandler);
			}
		}
	}
}
 